<div class="mx-4">
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-center font-bold text-xl mb-4">ログイン</h1>

        <hr class="mb-6">

        <form id="login-info" method="POST" action="/sessions/" class="flex flex-col gap-8 mb-10"
            enctype="multipart/form-data">
            <input type="hidden" name="csrf_token"
                value="<?= $data['csrf_token'] ?>">

            <div>
                <label for="email" class="font-bold">メールアドレス</label>
                <input id="email" aria-describedby="login-info email" type="email" name="email"
                    placeholder="sample@example.com"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                    aria-required="true">
            </div>

            <div>
                <label for="password" class="font-bold">パスワード</label>
                <input id="password" aria-describedby="login-info password" type="password" name="password"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                    aria-required="true">
            </div>

            <button type="submit"
                class="rounded-lg w-40 mx-auto text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 font-bold text-lg">登録</button>
        </form>

        <p class="text-center">
            初めての方は<a href="/users/new/" class="text-teal-800 hover:underline mx-1">新規登録</a>へ
        </p>
    </div>
</div>