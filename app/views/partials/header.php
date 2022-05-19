<?php

namespace App\views\partials;

class Header
{
    public static function render(bool $is_logged_in)
    {
        ?>

<header class="flex justify-between items-center p-6 shadow-md">
    <a href="/" class="text-xl inline-block">KENSHU TIMES</a>

    <?php if ($is_logged_in) { ?>
    <form action="/auth/logout/" method="POST">
        <button href="/auth/logout/" type="submit"
            class="px-3 py-1 text-sm text-orange-500 rounded-lg border border-orange-500 hover:bg-orange-50">ログアウト</button>
    </form>
    <?php } ?>

</header>

<?php
    }
}
