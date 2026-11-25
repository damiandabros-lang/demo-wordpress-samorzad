#!/bin/bash
# Uruchamia oficjalny entrypoint WordPressa, a w tle jednorazowo instaluje WP i treści demo.
set -euo pipefail
setup() {
  cd /var/www/html
  for i in $(seq 1 60); do
    [ -f wp-config.php ] && php -r '$h=explode(":",getenv("WORDPRESS_DB_HOST"));try{new mysqli($h[0],getenv("WORDPRESS_DB_USER"),getenv("WORDPRESS_DB_PASSWORD"),getenv("WORDPRESS_DB_NAME"),(int)($h[1]??3306));exit(0);}catch(Throwable $e){exit(1);}' && break
    sleep 3
  done
  if ! wp --allow-root core is-installed 2>/dev/null; then
    : "${WP_ADMIN_PASSWORD:?Ustaw WP_ADMIN_PASSWORD w Coolify}"
    wp --allow-root core install --url="${WP_SITE_URL:-https://demo-samorzad.isverostudio.com}" \
      --title="Gmina Przykładowo – DEMO" --admin_user="${WP_ADMIN_USER:-isvero_admin}" \
      --admin_password="$WP_ADMIN_PASSWORD" --admin_email="${WP_ADMIN_EMAIL:-admin@example.org}" --skip-email --locale=pl_PL
    wp --allow-root language core install pl_PL --activate || echo "Uwaga: nie pobrano tłumaczenia pl_PL (brak sieci?)"
  fi
  wp --allow-root theme activate isvero-samorzad
  wp --allow-root eval-file /usr/local/share/isv/seed.php
  wp --allow-root eval-file /usr/local/share/isv/aktualizacje.php
  wp --allow-root rewrite structure '/%postname%/' --hard >/dev/null 2>&1 || true
  chown -R www-data:www-data wp-content
  echo "[isv] setup zakończony"
}
setup > /proc/1/fd/1 2>&1 &
exec docker-entrypoint.sh "$@"
