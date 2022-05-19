<?php

namespace App\views\partials;

use App\models\User;

class UserInfo
{
    public function __construct(private User $user)
    {
    }

    public function render()
    {
        ?>
<div class="text-center mb-8">
    <img class="rounded-full w-40 h-40 inline-block"
        src="<?= $this->user->profile_image_url ?>" alt="プロフィール画像">
</div>
<table>
    <tr>
        <th class="text-left pr-4">ユーザー名</th>
        <td><?= $this->user->name ?>
        </td>
    </tr>
    <tr>
        <th class="text-left pr-4">メールアドレス</th>
        <td><?= $this->user->email ?>
        </td>
    </tr>
</table>
<?php
    }
}
