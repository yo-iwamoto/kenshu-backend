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

## 構成について
- データベースアクセスは Model 層からのみ行う。(Entity 層を挟みたいか抽象化がうまくいってない)
- 今のところ Controller から Model を扱うことを制限はしておらず、複数モデルを跨ぐ処理を含むものや複雑なもののみ Service を切っている。
- Model、Servie の外に出るエラーは全て ServerException とし、Controller で処理する (TODO)
