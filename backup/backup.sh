#!/bin/bash
# Codzienna kopia demo: baza (mariadb-dump) + wp-content. Rotacja: KEEP_DAYS dni.
while true; do
  ts=$(date +%Y%m%d-%H%M)
  if mariadb-dump -h db -u wordpress -p"$MARIADB_PASSWORD" --single-transaction --routines wordpress | gzip > "/backups/db-$ts.sql.gz" \
     && tar czf "/backups/wp-content-$ts.tar.gz" -C /wp wp-content; then
    echo "[backup] OK $ts"
  else
    echo "[backup] BLAD $ts"
  fi
  find /backups -type f -name '*.gz' -mtime "+${KEEP_DAYS:-14}" -delete
  sleep 86400
done
