<div class="mt-10 mx-4">
    <div class="max-w-5xl mx-auto">
        <section class="mb-12">
            <div class="flex justify-between items-center">
                <h1 class="mb-4 text-lg">記事投稿</h1>
                <button id="js-toggle-button" class="transition-transform">
                    <img src="/assets/img/arrow.png">
                </button>
            </div>
            <div id="js-toggle-body" class="overflow-hidden origin-top">
                <form id="post" class="flex flex-col h-auto gap-8" action="/posts/" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token"
                        value="<?= $data['csrf_token'] ?>" />

                    <div>
                        <label for="title"
                            class="font-bold before:content-['*'] before:text-red-500 before:pr-1">タイトル<small
                                class="pl-2">(最大100文字)</small></label>
                        <input id="title" aria-labelledby="post title" type="text" name="title" placeholder=""
                            class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                            aria-required="true">
                    </div>

                    <div>
                        <label for="content"
                            class="font-bold before:content-['*'] before:text-red-500 before:pr-1">本文</label>
                        <textarea id="content" aria-labelledby="post content" name="content" placeholder="" rows="6"
                            class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                            aria-required="true"></textarea>
                    </div>

                    <div class="inline-block relative w-full">
                        <label class="block" for="tags" class="font-bold">タグ<small class="pl-2">(任意,
                                複数選択可)</small></label>
                        <select id="tags" aria-labelledby="post tags" name="tags[]" multiple
                            class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg shadow-lg leading-tight focus:outline-none focus:shadow-outline">
                            <option value="general">総合</option>
                            <option value="technology">テクノロジー</option>
                            <option value="mobile">モバイル</option>
                            <option value="app">アプリ</option>
                            <option value="entertainment">エンタメ</option>
                            <option value="beauty">ビューティー</option>
                            <option value="fashion">ファッション</option>
                            <option value="life_style">ライフスタイル</option>
                            <option value="business">ビジネス</option>
                            <option value="gourmet">グルメ</option>
                            <option value="sports">スポーツ</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-bold" for="images" class="block">
                            <span>添付画像</span>
                            <small class="pl-2">(複数選択可・png, jpg, gif 形式のファイルを指定してください)</small>
                        </label>
                        <input id="images" aria-describedby="post images" type="file" accept="image/*" name="images[]"
                            multiple>
                    </div>

                    <input id="thumbnail_image_index" type="hidden" class="-mb-8" name="thumbnail_image_index">

                    <div id="js-preview-container" class="-mb-4 flex gap-2"></div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="rounded-lg w-16 text-white bg-teal-600 hover:bg-teal-500 transition-colors py-2 text-sm font-bold">投稿</button>
                    </div>
                </form>
            </div>
        </section>

        <hr class="mb-6">

        <section class="mb-20">
            <h1 class="mb-8 text-lg">記事一覧</h1>
            <div class="flex justify-around flex-wrap flex-grow gap-8">

                <?php if (count($data['posts']) === 0)  :?>

                <div class="text-center">
                    <p>まだ記事がないようです…🤔</p>
                    <p>上のフォームから何か書いてみましょう</p>
                </div>

                <?php else : ?>
                <?php foreach ($data['posts'] as $post) : ?>
                <a href="/posts/<?= $post->id ?>/"
                    class="block w-64">
                    <div class="col-span-1 rounded-lg p-4 shadow-lg hover:shadow-md">
                        <img class="h-20 w-20"
                            src="/<?= $post->thumbnail_url ?>"
                            alt="<?= htmlspecialchars($post->title) ?>">
                        <p class="font-bold text-lg mb-2">
                            <?= htmlspecialchars($post->title) ?>
                        </p>
                        <div class="flex justify-between items-end">
                            <p class="flex items-center">
                                <img class="h-5 w-5 rounded-full mr-1"
                                    src="<?= $post->user__profile_image_url ?>"
                                    alt="<?= htmlspecialchars($post->user__name) ?>">
                                <span class="text-xs text-gray-600">
                                    <?= htmlspecialchars($post->user__name) ?>
                                </span>
                            </p>
                            <form class="relative"
                                action="/posts/<?= $post->id ?>/"
                                method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="csrf_token"
                                    value="<?= $data['csrf_token'] ?>">

                                <button aria-label="記事を削除する"
                                    class="before:absolute before:-right-1/2 before:-top-12 before:text-sm before:hidden before:rounded-lg before:shadow-lg before:content-['削除'] before:text-white before:whitespace-nowrap before:p-2 before:bg-black before:opacity-60 hover:before:inline-block"
                                    type="submit">
                                    <img class="h-6 w-6" src="/assets/img/trash.png">
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
                <?php endforeach ?>

                <?php endif?>
            </div>
        </section>
    </div>
</div>
<script src=" /assets/js/toggle.js"></script>
<script src=" /assets/js/preview-multiple-images.js"></script>