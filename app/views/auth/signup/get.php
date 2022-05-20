<?php
use App\views\partials;

?>

<!DOCTYPE html>

<?php partials\Head::render() ?>

<body>
    <div class="font-mono">
        <?php partials\Header::render(is_logged_in: isset($_SESSION['user_id'])) ?>
        <main>
            <div class="mx-4">
                <div class="max-w-xl mx-auto mt-10">
                    <h1 class="text-center font-bold text-xl mb-4">新規会員登録</h1>

                    <hr class="mb-6">

                    <form method="POST" action="/auth/signup/" class="flex flex-col gap-8"
                        enctype="multipart/form-data">
                        <div>
                            <label for="name"
                                class="font-bold before:content-['*'] before:text-red-500 before:pr-1">ユーザー名</label>
                            <input id="name" type="text" name="name" placeholder="インターネット太郎"
                                class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
                        </div>

                        <div>
                            <label for="image" class="block">プロフィール画像<small class="pl-2">(png, jpg, gif
                                    形式のファイルを指定してください)</small></label>
                            <input id="image" type="file" accept="image/*" src="" alt="" name="profile_image">
                        </div>

                        <div id="js-preview-container"></div>

                        <div>
                            <label for="email"
                                class="font-bold before:content-['*'] before:text-red-500 before:pr-1">メールアドレス</label>
                            <input id="email" type="email" name="email" placeholder="sample@example.com"
                                class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
                        </div>

                        <div>
                            <label for="password"
                                class="font-bold before:content-['*'] before:text-red-500 before:pr-1">パスワード<small
                                    class="pl-2">(72文字以内の半角英数・記号で入力してください。)</small></label>
                            <input id="password" type="password" name="password" maxlength="72"
                                class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
                        </div>

                        <button type="submit"
                            class="rounded-lg w-40 mx-auto text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 font-bold text-lg">登録</button>
                    </form>
                    <script src="./get.js"></script>
                </div>
            </div>
        </main>
    </div>
</body>

</html>