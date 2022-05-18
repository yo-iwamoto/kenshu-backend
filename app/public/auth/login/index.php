<?php
require_once '../../../config/initialize.php';
require_once '../../../models/user.php';
require_once '../../../lib/redirect.php';
require_once '../../../lib/flash.php';

if (!isset($_SSSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = User::get_by_email($_POST['email']);
        if ($user->login($_POST['password'])) {
            $_SESSION['user_id'] = $user->id;

            $_SESSION['flash'] = Lib\Flash::register(message: 'ログインしました');
            redirect_tmp('/');
        } else {
            Lib\Flash::register(message: 'メールアドレスかパスワードが誤っています', type: 'error');
        }
    }
}

?>

<!DOCTYPE html>

<html lang="ja">

<?php include '../../partials/head.php' ?>

<body>
    <?php include '../../partials/flash.php' ?>
    <?php include '../../partials/header.php' ?>
    <main class="font-mono mx-4">
        <div class="max-w-xl mx-auto mt-10">
            <h1 class="text-center font-bold text-xl mb-4">ログイン</h1>

            <hr class="mb-6">

            <form method="POST" action="/auth/login/" class="flex flex-col gap-8" enctype="multipart/form-data">
                <div>
                    <label for="email" class="font-bold">メールアドレス</label>
                    <input id="email" type="email" name="email" placeholder="sample@example.com"
                        class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
                </div>

                <div>
                    <label for="password" class="font-bold">パスワード</label>
                    <input id="password" type="password" name="password"
                        class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
                </div>

                <button type="submit"
                    class="rounded-lg w-40 mx-auto text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 font-bold text-lg">登録</button>
            </form>
        </div>
    </main>
</body>

</html>