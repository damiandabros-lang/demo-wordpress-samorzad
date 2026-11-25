#!/usr/bin/env python3
"""Structural regression checks for all demo PDFs and equivalent HTML sources.

Run with pypdf==7.1.1. This checks specific regressions, not complete PDF/UA compliance.
"""
from html.parser import HTMLParser
from pathlib import Path
import re
from pypdf import PdfReader

ROOT = Path(__file__).resolve().parents[1]
DOCS = ROOT / 'wp/theme/isvero-samorzad/assets/docs'

class MainText(HTMLParser):
    def __init__(self):
        super().__init__()
        self.active = False
        self.skip = False
        self.text = []
    def handle_starttag(self, tag, attrs):
        if tag == 'main': self.active = True
        if tag == 'p' and ('class', 'screen-only') in attrs: self.skip = True
    def handle_endtag(self, tag):
        if tag == 'main': self.active = False
        if tag == 'p': self.skip = False
    def handle_data(self, data):
        if self.active and not self.skip: self.text.append(data)


def normalized(s):
    return re.sub(r'\s+', '', s)


def roles(obj):
    obj = obj.get_object() if hasattr(obj, 'get_object') else obj
    if isinstance(obj, list):
        return [r for child in obj for r in roles(child)]
    if isinstance(obj, dict):
        return ([str(obj['/S'])] if '/S' in obj else []) + roles(obj.get('/K', []))
    return []

pdfs = sorted(DOCS.glob('*.pdf'))
assert len(pdfs) == 6
for path in pdfs:
    reader = PdfReader(path)
    root = reader.trailer['/Root']
    assert str(root['/Lang']) == 'pl', path
    assert root['/MarkInfo']['/Marked'], path
    tags = roles(root['/StructTreeRoot'])
    assert tags.count('/H1') == 1 and tags.count('/H2') == 2 and '/P' in tags, (path, tags)
    assert reader.metadata.title and reader.metadata.title.endswith('(DEMO)'), path
    assert len(reader.pages) == 1, path
    text = '\n'.join(page.extract_text() for page in reader.pages)
    source = path.with_suffix('.html').read_text()
    assert '<html lang="pl">' in source and 'noindex, nofollow' in source
    parser = MainText(); parser.feed(source)
    assert normalized(' '.join(parser.text)) in normalized(text), path
    assert 'Dokument pokazuje sposób publikowania załączników.' in text
    assert 'wyłącznie dane fikcyjne' in text
    print(f'PASS {path.name}: PL, tytuł, tagi H1/H2/P, 1 strona, pełna treść HTML w PDF')
