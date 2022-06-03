<div class="mx-4">
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-center font-bold text-xl mb-4">新規会員登録</h1>

        <hr class="mb-6">

        <form id="user" method="POST" action="/users/" class="flex flex-col gap-8 mb-10" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token"
                value="<?= $data['csrf_token'] ?>">

            <div>
                <label for="name" class="font-bold before:content-['*'] before:text-red-500 before:pr-1">ユーザー名</label>
                <input id="name" aria-describedby="user name" type="text" name="name" placeholder="インターネット太郎"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                    aria-required="true">
            </div>

            <div>
                <label for="image" class="block">プロフィール画像<small class="pl-2">(png, jpg, gif
                        形式のファイルを指定してください)</small></label>
                <input id="image" aria-describedby="user image" type="file" accept="image/*" src="" alt=""
                    name="profile_image">
            </div>

            <div id="js-preview-container"></div>

            <div>
                <label for="email"
                    class="font-bold before:content-['*'] before:text-red-500 before:pr-1">メールアドレス</label>
                <input id="email" aria-describedby="user email" type="email" name="email"
                    placeholder="sample@example.com"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                    aria-required="true">
            </div>

            <div>
                <label for="password" class="font-bold before:content-['*'] before:text-red-500 before:pr-1">パスワード<small
                        class="pl-2">(72文字以内の半角英数・記号で入力してください。)</small></label>
                <input id="password" aria-describedby="user password" type="password" name="password" maxlength="72"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                    aria-required="true">
            </div>

            <button type="submit"
                class="rounded-lg w-40 mx-auto text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 font-bold text-lg">登録</button>
        </form>

        <p class="text-center">
            既にアカウントをお持ちの方は<a href="/sessions/new/" class="text-teal-800 hover:underline mx-1">ログイン</a>へ
        </p>

        <script src="/assets/js/preview-image.js"></script>
    </div>
</div>