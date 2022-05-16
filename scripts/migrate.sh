source ./.env

docker-compose run db psql \
  postgresql://$POSTGRES_DEV_USER:$POSTGRES_DEV_PASSWORD@db:5432/$POSTGRES_DEV_DATABASE \
  -c "\i /var/lib/postgresql/migrations/$1"
