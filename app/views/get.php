<div class="mx-4">
    <div class="max-w-xl mx-auto">
        <?php if (!$is_authenticated) { ?>

        <p class="text-center mt-6">
            <span class="whitespace-nowrap">KENSHU TIMESでは</span>
            <span class="whitespace-nowrap">なんと、画像付きの記事が投稿できます</span>
        </p>
        <div class="flex justify-center gap-8 my-10">
            <div class="text-center">
                <p class="mb-2">まずは</p>
                <a class="text-white px-4 py-2 bg-teal-600 inline-block hover:bg-teal-500 rounded-lg"
                    href="/auth/signup/">新規会員登録</a>
            </div>
            <div class="text-center">
                <p class="mb-2">または</p>
                <a class="text-teal-600 px-4 py-2 border border-teal-600 inline-block hover:bg-teal-50 rounded-lg"
                    href="/auth/login/">ログイン</a>
            </div>
        </div>

        <?php } ?>
    </div>

    <section class="mt-8">
        <h1 class="font-bold text-xl">記事一覧</h1>
    </section>
</div>