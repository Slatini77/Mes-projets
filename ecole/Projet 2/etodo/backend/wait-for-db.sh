#!/bin/sh
set -e

echo "⏳ Waiting for MySQL to be ready at $MYSQL_HOST ..."

while ! mysqladmin ping -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent; do
  sleep 1
  printf "."
done

echo ""
echo "✔️ MySQL is ready!"

exec "$@"

