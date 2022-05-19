<?php

namespace App\views\partials;

use App\models\User;

class UserInfo
{
    public static function render(User $user)
    {
        ?>
<div class="text-center mb-8">
    <img class="rounded-full w-40 h-40 inline-block"
        src="<?= $user->profile_image_url ?>" alt="プロフィール画像">
</div>
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
