"""Wdrożenie demo jako OSOBNEJ aplikacji Coolify (Docker Compose) z publicznego repo.

- Tworzy projekt "Isvero DEMO portfolio" i aplikację, jeśli ich nie ma (nie dotyka innych aplikacji).
- Generuje losowe sekrety tylko przy pierwszym utworzeniu (nigdy ich nie wypisuje).
- Wdraża dokładny SHA i sprawdza HTTPS + baner DEMO.
Token COOLIFY_TOKEN pochodzi wyłącznie z sekretu GitHub Actions.
"""
import json, os, re, secrets, sys, time, urllib.error, urllib.request
from pathlib import Path

BASE = os.environ.get('COOLIFY_URL', 'https://coolify.148.113.245.121.sslip.io').rstrip('/') + '/api/v1'
TOKEN = os.environ['COOLIFY_TOKEN']
SHA = os.environ['GITHUB_SHA']
REPO = os.environ['GITHUB_REPOSITORY']
CFG = json.loads(Path('deploy/app.json').read_text())
PROJECT_NAME = 'Isvero DEMO portfolio'
SERVER_IP = os.environ.get('COOLIFY_SERVER_IP', '148.113.245.121')
assert re.fullmatch('[0-9a-f]{40}', SHA)
assert REPO.split('/')[1] == CFG['name'], 'deploy/app.json name must equal repo name'


def api(path, data=None, method=None):
    req = urllib.request.Request(BASE + path, data=json.dumps(data).encode() if data is not None else None,
                                 headers={'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                                 method=method or ('POST' if data is not None else 'GET'))
    try:
        with urllib.request.urlopen(req, timeout=60) as r:
            body = r.read()
            return json.loads(body) if body else {}
    except urllib.error.HTTPError as e:
        raise RuntimeError(f'{method or "GET"} {path} -> {e.code}: {e.read()[:400]!r}') from None


def log(msg):
    print('[deploy] ' + msg, flush=True)


# 1. Serwer
servers = api('/servers')
server = next((s for s in servers if s.get('ip') == SERVER_IP), None) or (servers[0] if len(servers) == 1 else None)
assert server, 'Nie znaleziono serwera Coolify'

# 2. Projekt (osobny dla dem)
projects = api('/projects')
project = next((p for p in projects if p.get('name') == PROJECT_NAME), None)
if not project:
    project = api('/projects', {'name': PROJECT_NAME, 'description': 'Dema portfolio Isvero Studio (2026). Nie są to realizacje dla klientów.'})
    log('utworzono projekt ' + PROJECT_NAME)
project_uuid = project['uuid']

# 3. Aplikacja
apps = api('/applications')
app = next((a for a in apps if a.get('name') == CFG['name']), None)
domain = CFG.get('domain') or f"https://{CFG['subdomain']}.{SERVER_IP}.sslip.io"
created = False
if not app:
    payload = {
        'project_uuid': project_uuid, 'server_uuid': server['uuid'], 'environment_name': 'production',
        'git_repository': f'https://github.com/{REPO}', 'git_branch': 'main', 'git_commit_sha': SHA,
        'build_pack': 'dockercompose', 'docker_compose_location': CFG.get('compose', '/compose.yaml'),
        'ports_exposes': str(CFG.get('port', 80)), 'name': CFG['name'],
        'description': 'DEMO / portfolio Isvero Studio (2026)', 'instant_deploy': False,
    }
    app = api('/applications/public', payload)
    created = True
    log('utworzono aplikację ' + CFG['name'])
uuid = app['uuid']

# 4. Zmienne: stałe z app.json + losowe sekrety tylko przy pierwszym razie
existing = {e['key'] for e in api(f'/applications/{uuid}/envs')}
envs = dict(CFG.get('env', {}))
if CFG.get('wordpress'):
    envs.setdefault('WP_SITE_URL', domain)
for key in CFG.get('generate_secrets', []):
    if key not in existing:
        envs[key] = secrets.token_urlsafe(32)
for k, v in envs.items():
    if k in existing and k in CFG.get('generate_secrets', []):
        continue
    if k in existing:
        api(f'/applications/{uuid}/envs', {'key': k, 'value': str(v)}, method='PATCH')
    else:
        api(f'/applications/{uuid}/envs', {'key': k, 'value': str(v), 'is_preview': False})
log(f'zmienne ustawione ({len(envs)})')

# 5. Domena tylko dla usługi WWW + dokładny SHA
api(f'/applications/{uuid}', {
    'git_commit_sha': SHA,
    'docker_compose_domains': [{'name': CFG['web_service'], 'domain': domain}],
}, method='PATCH')

# 6. Wdrożenie
res = api(f'/deploy?uuid={uuid}&force=false')
dep = res['deployments'][0]['deployment_uuid']
log('wdrożenie ' + dep)
for _ in range(120):
    st = api('/deployments/' + dep)
    if st['status'] == 'finished':
        break
    if st['status'] not in ('queued', 'in_progress'):
        raise RuntimeError('Wdrożenie: ' + st['status'])
    time.sleep(10)
else:
    raise TimeoutError('Wdrożenie trwa zbyt długo')

# 7. Weryfikacja HTTPS i banera DEMO
check = domain + CFG.get('check_path', '/')
for i in range(36):
    try:
        with urllib.request.urlopen(check, timeout=30) as r:
            body = r.read().decode('utf-8', 'replace')
            if r.status == 200 and 'DEMO' in body:
                log(f'OK {check}')
                Path('deploy-report.json').write_text(json.dumps({'app': CFG['name'], 'uuid': uuid, 'url': domain, 'sha': SHA, 'created': created}, indent=2))
                sys.exit(0)
    except Exception as e:  # noqa: BLE001
        log(f'czekam na start ({i}): {type(e).__name__}')
    time.sleep(10)
raise SystemExit('Strona nie odpowiada poprawnie: ' + check)
