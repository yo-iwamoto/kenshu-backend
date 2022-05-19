<?php
require_once '../../../lib/initialize.php';

use Ramsey\Uuid\Uuid;

use App\models\User;
use App\views\partials;

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $file_path = '';
    
    if (isset($_FILES['profile_image'])) {
        $file_path = '/assets/img/users/' . Uuid::uuid4() . '_' . $_FILES['profile_image']['name'];
    }

    try {
        User::create(
            email: $_POST['email'],
            name: $_POST['name'],
            password: $_POST['password'],
            profile_image_url: $file_path,
        );

        move_uploaded_file($_FILES['profile_image']['tmp_name'], '../..' . $file_path);

        $user = User::get_by_email($_POST['email']);

        $_SESSION['user_id'] = $user->id;
    } catch (Exception $err) { // TODO: エラーの種類を拾う
        // TODO: 登録フォームを保ったままエラーを表示
        // TODO: 既にユーザーが存在する時、ログインページへ遷移
        print_r($err);
    }
}

?>

<!DOCTYPE html>

<?php partials\Head::render() ?>

<body>
    <div class="font-mono">
        <?php partials\Header::render(is_logged_in: isset($_SESSION['user_id'])) ?>
        <main>
            <div class="mx-4">
                <div class="max-w-xl mx-auto mt-10">
                    <?php if ($method === 'GET') { ?>

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
                    <script src="./index.js"></script>

                    <?php } else { ?>

                    <section class="my-4">
                        <?php include '../../partials/user.php' ?>
                    </section>

                    <?php } ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>