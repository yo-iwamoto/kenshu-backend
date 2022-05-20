<?php
namespace App\services;

use App\lib\Request;
use App\models\User;
use Exception;
use Ramsey\Uuid\Uuid;

class SignupService
{
    public static function execute(Request $request)
    {
        $uploaded_file_path = null;

        if ($request->files['profile_image']['size'] !== 0) {
            self::verifyUploadedImageFile($request->files['profile_image']);
            $uploaded_file_path = '/assets/img/users/' . Uuid::uuid4() . '_' . $request->files['profile_image']['name'];
        }

        $profile_image_url = $uploaded_file_path !== null ? $uploaded_file_path : '/assets/img/default-icon.jpg';

        User::create(
            email: $request->post['email'],
            name: $request->post['name'],
            password: $request->post['password'],
            profile_image_url: $profile_image_url,
        );

        if ($uploaded_file_path) {
            move_uploaded_file($request->files['profile_image']['tmp_name'], '../..' . $uploaded_file_path);
        }
            
        $user = User::get_by_email($request->post['email']);

        $request->setSession('user_id', $user->id);

        return $user;
    }

    /**
     * throws Exception when invalid
     */
    private static function verifyUploadedImageFile(array $file)
    {
        // ファイル形式の確認
        $valid_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        if (!in_array(mime_content_type($file['tmp_name']), $valid_types, true)) {
            throw new Exception('invalid file type');
        }

        // 画像サイズの確認
        if ($file['size'] > 1000000) {
            throw new Exception('file size is too big');
        }
    }
}
