<?php $post = $data['post'] ?>

<div class="mt-10 mx-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="/posts/" class="hover:underline text-teal-800">
                &lt; 記事一覧画面に戻る
            </a>
        </div>

        <h1 class="font-bold text-3xl mb-4">
            <?= $post->title ?>
        </h1>

        <div class="flex justify-between items-end">
            <div>
                <p class="flex items-center mb-1">
                    <img class="h-5 w-5 rounded-full mr-1"
                        src="<?= $post->user_profile_image_url ?>"
                        alt="<?= $post->user_name ?>">
                    <span class="text-gray-600 text-sm">
                        <?= $post->user_name ?>
                    </span>
                </p>
                <p class="text-sm">
                    <span>作成日時: </span>
                    <span><?= date_format(new DateTime($post->created_at), 'Y-m-d H:i:s') ?></span>
                </p>
            </div>

            <a class="before:absolute before:right-16 before:text-sm before:hidden before:rounded-lg before:shadow-lg before:content-['編集'] before:text-white before:whitespace-nowrap before:p-2 before:bg-black before:opacity-60 hover:before:inline-block"
                href="/posts/<?= $post->id ?>/edit/">
                <img class="h-10 w-10" src="/assets/img/edit.png">
            </a>
        </div>

        <hr class="mt-4 pb-10">

        <p>
            <?= nl2br(htmlspecialchars($post->content)) ?>
        </p>
    </div>
</div>