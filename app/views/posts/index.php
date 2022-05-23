<div class="mt-10 mx-4">
    <div class="max-w-5xl mx-auto">
        <section class="mb-12">
            <div class="flex justify-between items-center">
                <h1 class="mb-4 text-lg">記事投稿</h1>
                <button id="js-toggle-button" class="transition-transform">
                    <img src="/assets/img/arrow.png">
                </button>
            </div>
            <form id="js-toggle-body" class="flex flex-col h-auto gap-8 overflow-hidden origin-top transition-all"
                action="/posts/" method="POST">
                <div>
                    <label for="title" class="font-bold before:content-['*'] before:text-red-500 before:pr-1">タイトル<small
                            class="pl-2">(最大100文字)</small></label>
                    <input id="title" type="text" name="title" placeholder=""
                        class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required>
                </div>

                <div>
                    <label for="content"
                        class="font-bold before:content-['*'] before:text-red-500 before:pr-1">本文</label>
                    <textarea id="content" name="content" placeholder="" rows="6"
                        class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="rounded-lg w-16 text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 text-sm font-bold">投稿</button>
                </div>
            </form>
        </section>

        <hr class="mb-6">

        <section>
            <h1 class="mb-8 text-lg">記事一覧</h1>
            <div class="flex justify-around flex-wrap flex-grow gap-8">

                <?php foreach ($data['posts'] as $post) { ?>
                <a href="/posts/<?= $post->id ?>/"
                    class="block w-80">
                    <div class="col-span-1 rounded-lg p-4 shadow-lg hover:shadow-md">
                        <img class="h-20 w-20"
                            src="/<?= $post->thumbnail_url ?>"
                            alt="<?= $post->title ?>">
                        <p class="font-bold text-lg mb-2">
                            <?= $post->title ?>
                        </p>
                        <p class="flex items-center">
                            <img class="h-5 w-5 rounded-full mr-1"
                                src="<?= $post->user_profile_image_url ?>"
                                alt="<?= $post->user_name ?>">
                            <span class="text-xs text-gray-600">
                                <?= $post->user_name ?>
                            </span>
                        </p>
                    </div>
                </a>
                <?php } ?>
            </div>
        </section>
    </div>
</div>
<script src=" /assets/js/toggle.js"></script>