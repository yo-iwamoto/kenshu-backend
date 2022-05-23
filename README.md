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

また、このコマンドでユーザーを作成しているので、新規登録しなくても http://localhost:180/sessions/new/ から以下のユーザーでログイン可能です。
```
email: sample@example.com
password: password
```

## マイグレーション
```
vim db/data/yyyymmddhhmm_xxx.sql

./scripts/execute_sql.sh migrations/<filename>
```
