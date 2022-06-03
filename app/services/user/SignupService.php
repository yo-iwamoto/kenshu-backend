<?php
namespace App\services\user;

use App\dto\CreateUserDto;
use App\models\User;
use App\services\common\ValidateUploadedImageService;
use App\services\concerns\Service;
use Ramsey\Uuid\Uuid;

class SignupService extends Service
{
    public function execute()
    {
        $pdo = $this->pdo;
        $request = $this->request;

        $uploaded_file_path = null;

        if ($request->files['profile_image']['size'] !== 0) {
            ValidateUploadedImageService::execute($request->files['profile_image']);
            $uploaded_file_path = '/assets/img/users/' . Uuid::uuid4() . '_' . $request->files['profile_image']['name'];
        }

        // アップロードされた画像がない時、デフォルトの画像を設定する
        $profile_image_url = $uploaded_file_path !== null ? $uploaded_file_path : '/assets/img/default-icon.png';

        $dto = new CreateUserDto(
            name: $request->post['name'],
            email: $request->post['email'],
            password: $request->post['password'],
            profile_image_url: $profile_image_url,
        );

        User::create($pdo, $dto);

        if ($uploaded_file_path) {
            move_uploaded_file($request->files['profile_image']['tmp_name'], '../public' . $uploaded_file_path);
        }

        $user = User::getByEmail($pdo, $request->post['email']);

        $request->setSession('user_id', $user->id);

        return $user;
    }
}
