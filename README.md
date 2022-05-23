# kenshu-backend

## コンテナ起動
```
cp .env.example ./.env

docker-compose build

docker-compose up -d

make init
make migrate-all
make seed
```

`make seed` の実行は結果的には冪等ですが2回目以降はエラーを吐きます。

## マイグレーション
```
vim db/data/yyyymmddhhmm_xxx.sql

./scripts/execute_sql.sh migrations/<filename>
```
