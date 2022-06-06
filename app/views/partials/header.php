<?php

namespace App\views\partials;

class Header
{
    public static function render(bool $is_authenticated, string $csrf_token)
    {
        ?>

<header class="flex justify-between items-center p-6 shadow-md">
    <a href="/" class="text-xl inline-block">KENSHU TIMES</a>

    <?php if ($is_authenticated) : ?>
    <form action="/sessions/_/" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="csrf_token"
            value="<?= $csrf_token ?>">

        <button type="submit" aria-label="ログアウトする"
            class="before:absolute before:right-8 before:text-sm before:hidden before:rounded-lg before:shadow-lg before:content-['ログアウト'] before:text-white before:whitespace-nowrap before:p-2 before:bg-black before:opacity-60 hover:before:inline-block">
            <img class="h-6 w-6" src="/assets/img/logout.png" alt="ログアウト">
        </button>
    </form>

    <?php else : ?>
    <div class="flex justify-center gap-8">
        <a class="text-white px-4 py-2 bg-teal-600 inline-block hover:bg-teal-500 rounded-lg transition-colors"
            href="/users/new/">新規会員登録</a>
        <a class="text-teal-600 px-4 py-2 border border-teal-600 inline-block hover:bg-teal-50 rounded-lg transition-colors"
            href="/sessions/new/">ログイン</a>
    </div>

    <?php endif ?>

</header>

<?php
    }
}
