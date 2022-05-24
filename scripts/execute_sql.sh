source ./.env

docker-compose run --rm db psql \
  postgresql://$POSTGRES_DEV_USER:$POSTGRES_DEV_PASSWORD@db:$POSTGRES_DEV_PORT/$POSTGRES_DEV_DATABASE \
  -c "\i /var/lib/postgresql/$1"
