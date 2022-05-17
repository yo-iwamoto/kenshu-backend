<?php
require_once '../config/initialize.php';
require_once '../models/user.php';

use Ramsey\Uuid\Uuid;

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $file_path = './assets/img/users/' . Uuid::uuid4() . '_' . $_FILES['profile_image']['name'];
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $file_path);

    try {
        User::create(array(
            'email' => $_POST['email'],
            'name' => $_POST['name'],
            'password' => $_POST['password'],
            'profile_image_url' => $file_path,
        ));

        $user = User::get_by_email($_POST['email']);
    } catch (Exception $err) {
        // TODO: 登録フォームを保ったままエラーを表示

        // TODO: upload したファイルの削除
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php include './partials/head.php' ?>

<body>
    <main class="mx-4 font-mono">
        <div class="max-w-xl mx-auto mt-10">
            <?php if ($method === 'GET') { ?>

            <h1 class="text-center font-bold text-xl">新規会員登録</h1>
            <?php include './partials/register_form.php' ?>
            <script src="assets/js/index.js"></script>

            <?php } else { ?>

            <h1 class="text-center font-bold text-xl">登録が完了しました</h1>
            <section class="my-4">
                <?php include './partials/user.php' ?>
            </section>

            <?php } ?>
        </div>
    </main>
</body>

</html>