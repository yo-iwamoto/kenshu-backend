<?php
use App\models\User;
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
                    <section class="my-4">
                        <!-- TODO: view からアクセスするデータをまとめる -->
                        <?php partials\UserInfo::render(User::get_by_id($_SESSION['user_id'])) ?>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>

</html>