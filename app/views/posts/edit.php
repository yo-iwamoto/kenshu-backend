<?php
$post = $data['post'];

?>

<div class="mt-10 mx-8">
    <div class="max-w-5xl mx-auto">
        <form id="post" action="/posts/<?= $post->id ?>/"
            method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="csrf_token"
                value="<?= $data['csrf_token'] ?>">

            <div class="mb-8">
                <label for="title" class="font-bold before:content-['*'] before:text-red-500 before:pr-1">タイトル<small
                        class="pl-2">(最大100文字)</small></label>
                <input id="title" aria-describedby="post title" type="text" name="title" placeholder=""
                    class="border-gray-400 text-2xl font-bold w-full shadow-lg border rounded-lg bg-light-800 p-2"
                    value="<?= htmlspecialchars($post->title) ?>"
                    required aria-required="true">
            </div>

            <div class="inline-block relative w-full mb-8">
                <label class="block" for="tags" class="font-bold">タグ<small class="pl-2">(任意, 複数選択可)</small></label>
                <select id="tags" aria-describedby="post tags" name="tags[]" multiple
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg shadow-lg leading-tight focus:outline-none focus:shadow-outline">
                    <option value="general" <?= in_array('general', $data['tag_ids']) ? 'selected' : '' ?>>総合
                    </option>
                    <option value="technology" <?= in_array('technology', $data['tag_ids']) ? 'selected' : '' ?>>テクノロジー
                    </option>
                    <option value="mobile" <?= in_array('mobile', $data['tag_ids']) ? 'selected' : '' ?>>モバイル
                    </option>
                    <option value="app" <?= in_array('app', $data['tag_ids']) ? 'selected' : '' ?>>アプリ
                    </option>
                    <option value="entertainment" <?= in_array('entertainment', $data['tag_ids']) ? 'selected' : '' ?>>エンタメ
                    </option>
                    <option value="beauty" <?= in_array('beauty', $data['tag_ids']) ? 'selected' : '' ?>>ビューティー
                    </option>
                    <option value="fashion" <?= in_array('fashion', $data['tag_ids']) ? 'selected' : '' ?>>ファッション
                    </option>
                    <option value="life_style" <?= in_array('life_style', $data['tag_ids']) ? 'selected' : '' ?>>ライフスタイル
                    </option>
                    <option value="business" <?= in_array('business', $data['tag_ids']) ? 'selected' : '' ?>>ビジネス
                    </option>
                    <option value="gourmet" <?= in_array('gourmet', $data['tag_ids']) ? 'selected' : '' ?>>グルメ
                    </option>
                    <option value="sports" <?= in_array('sports', $data['tag_ids']) ? 'selected' : '' ?>>スポーツ
                    </option>
                </select>
            </div>

            <div class="flex justify-between items-end">
                <div>
                    <p class="flex items-center mb-1">
                        <img class="h-5 w-5 rounded-full mr-1"
                            src="<?= $post->user__profile_image_url ?>"
                            alt="<?= htmlspecialchars($post->user__name) ?>">
                        <span class="text-gray-600 text-sm">
                            <?= htmlspecialchars($post->user__name) ?>
                        </span>
                    </p>
                    <p class="text-sm">
                        <span>作成日時: </span>
                        <span><?= date_format(new DateTime($post->created_at), 'Y-m-d H:i:s') ?></span>
                    </p>
                </div>

                <div class="flex items-start gap-4">
                    <span class="relative">
                        <a aria-label="キャンセル"
                            href="/posts/<?= $post->id ?>/"
                            class="before:absolute before:-right-0.5 before:-top-12 before:text-sm before:hidden before:rounded-lg before:shadow-lg before:content-['キャンセル'] before:text-white before:whitespace-nowrap before:p-2 before:bg-black before:opacity-60 hover:before:inline-block">
                            <img class="h-10 w-10" src="/assets/img/cancel.png">
                        </a>
                    </span>

                    <span class="relative">
                        <button type="submit" aria-label="変更を保存する"
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
                <textarea id="content" aria-describedby="post content" name="content" placeholder="" rows="6"
                    class="border-gray-400 w-full shadow-lg border rounded-lg bg-light-800 p-2" required
                    aria-required="true"><?= htmlspecialchars($post->content) ?></textarea>
            </div>
            </p>
        </form>
    </div>
</div>