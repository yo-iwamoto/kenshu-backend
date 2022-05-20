# kenshu-backend

## マイグレーション
```
vim db/data/yyyymmddhhmm_xxx.sql

./scripts/migrate.sh <filename>
```

## コンテナ起動
```
cp .env.example ./.env

docker-compose build

docker-compose up -d

make init
```
