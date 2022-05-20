<div class="mt-10 mx-4">
    <div class="max-w-5xl mx-auto">
        <h1 class="mb-8">記事一覧</h1>
        <div class="flex justify-around flex-wrap flex-grow gap-8">

            <?php foreach ($posts as $post) { ?>
            <a href="/posts/<?= $post->id ?>" class="block w-80">
                <div class="col-span-1 rounded-lg p-4 shadow-lg hover:shadow-md">
                    <img class="h-20 w-20"
                        src="/<?= $post->thumbnail_url ?>"
                        alt="<?= $post->title ?>">
                    <p class="font-bold text-lg">
                        <?= $post->title ?>
                    </p>
                </div>
            </a>
            <?php } ?>
        </div>
    </div>
</div>