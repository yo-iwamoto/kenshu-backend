<?php

namespace App\views\partials;

use App\models\User;

class UserInfo
{
    public static function render(?User $user)
    {
        if ($user === null) {
            ?>
<span></span>
<?php
        } else { ?>
<div class="text-center mb-8">
    <img class="rounded-full w-40 h-40 inline-block"
        src="<?= $user->profile_image_url !== null ? $user->profile_image_url : 'assets/img/default-icon.jpg' ?>"
        alt="プロフィール画像">
</div>
<?php if (str_contains($user->profile_image_url, 'default-icon')) { ?>
<a class="mb-10" href="https://jp.freepik.com/vectors/animal">Freepik - jp.freepik.com によって作成された animal ベクトル</a>
<?php } ?>
<table>
    <tr>
        <th class="text-left pr-4">ユーザー名</th>
        <td><?= $user->name ?>
        </td>
    </tr>
    <tr>
        <th class="text-left pr-4">メールアドレス</th>
        <td><?= $user->email ?>
        </td>
    </tr>
</table>
<?php
}
    }
}
