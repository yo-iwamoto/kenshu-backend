<?php
use App\views\partials;

?>

<div class="mx-4">
    <div class="max-w-xl mx-auto mt-10">
        <section class="my-4">
            <!-- TODO: view からアクセスするデータをまとめる -->
            <?php partials\UserInfo::render($current_user) ?>
        </section>
    </div>
</div>