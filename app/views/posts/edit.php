<?php $post = $data['post'] ?>

<div class="mt-10 mx-8">
    <div class="max-w-5xl mx-auto">
        <form action="/posts/<?= $post->id ?>/" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="csrf_token"
                value="<?= $data['csrf_token'] ?>">

            <div class="mb-8">
                <label for="title" class="font-bold before:content-['*'] before:text-red-500 before:pr-1">タイトル<small
                        class="pl-2">(最大100文字)</small></label>
                <input id="title" type="text" name="title" placeholder=""
                    class="border-gray-400 text-2xl font-bold w-full shadow-lg border rounded-lg bg-light-800 p-2"
                    value="<?= htmlspecialchars($post->title) ?>"
                    required>
            </div>

            <div class="flex justify-between items-end">
                <div>
                    <p class="flex items-center mb-1">
                        <img class="h-5 w-5 rounded-full mr-1"
                            src="<?= $post->user_profile_image_url ?>"
                            alt="<?= htmlspecialchars($post->user_name) ?>">
                        <span class="text-gray-600 text-sm">
                            <?= htmlspecialchars($post->user_name) ?>
                        </span>
                    </p>
                    <p class="text-sm">
                        <span>作成日時: </span>
                        <span><?= date_format(new DateTime($post->created_at), 'Y-m-d H:i:s') ?></span>
                    </p>
                </div>

                <div class="flex items-start gap-4">
                    <span class="relative">
                        <a href="/posts/<?= $post->id ?>/"
                            class="before:absolute before:-right-0.5 before:-top-12 before:text-sm before:hidden before:rounded-lg before:shadow-lg before:content-['キャンセル'] before:text-white before:whitespace-nowrap before:p-2 before:bg-black before:opacity-60 hover:before:inline-block">
                            <img class="h-10 w-10" src="/assets/img/cancel.png">
                        </a>
                    </span>

                    <span class="relative">
                        <button type="submit"
                            class="before:absolute before:-right-0.5 before:-top-12 before:text-sm before:hidden before:rounded-lg before:shadow-lg before:content-['保存'] before:text-white before:whitespace-nowrap before:p-2 before:bg-black before:opacity-60 hover:before:inline-block">
                            <img class=" h-10 w-10" src="/assets/img/save.png">
                        </button>
                    </span>
                </div>
            </div>

            <hr class="mt-4 pb-10">

            <p>
            <div>
                <label for="content" class="font-bold before:content-['*'] before:text-red-500 before:pr-1">本文</label>
                <textarea id="content" name="content" placeholder="" rows="6"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2"
                    required><?= htmlspecialchars($post->content) ?></textarea>
            </div>
            </p>
        </form>
    </div>
</div>