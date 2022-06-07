<?php
namespace App\services\post;

use App\dto\CreatePostDto;
use App\lib\Request;
use App\lib\ServerException;
use App\models\Post;
use App\models\PostImage;
use App\models\PostToTag;
use App\services\common\ValidateUploadedImageService;
use App\services\concerns\Service;
use Ramsey\Uuid\Nonstandard\Uuid;

use Exception;

class CreateService extends Service
{
    public function execute(Request $request)
    {
        $pdo = $this->pdo;

        $current_user_id = $request->getCurrentUserId();

        try {
            $dto = new CreatePostDto(
                user_id: $current_user_id,
                title: $request->post['title'],
                content: $request->post['content'],
                tags: isset($request->post['tags']) ? $request->post['tags'] : [],
            );

            $pdo->beginTransaction();

            $post_id = Post::create($pdo, $dto);
            $post = Post::getById($pdo, $post_id);

            // タグの追加
            // TODO: O(n) の回避
            foreach ($dto->tags as $tag_id) {
                PostToTag::create($pdo, $post->id, $tag_id);
            }

            $uploaded_images = $request->files['images'];
            $is_uploaded_images_empty = $uploaded_images['tmp_name'][0] === '';
            if (!$is_uploaded_images_empty) {
                // アップロードされた枚数分繰り返し
                // TODO: O(n) の回避
                for ($index = 0; $index < count($uploaded_images['tmp_name']); $index ++) {
                    $file = array(
                        'name' => $uploaded_images['name'][$index],
                        'tmp_name' => $uploaded_images['tmp_name'][$index],
                        'size' => $uploaded_images['size'][$index],
                    );
                    ValidateUploadedImageService::execute($file);
                    // 画像パスの指定
                    $file_path = '/assets/img/posts/' . Uuid::uuid4() . '_' . $file['name'];
    
                    $post_image_id = PostImage::create($pdo, $post_id, $file_path);
                    // 画像の保存
                    move_uploaded_file($file['tmp_name'], '../public' . $file_path);
    
                    if ($index === intval($request->post['thumbnail_image_index'])) {
                        $post->updateThumbnailPostImageId($pdo, $post_image_id);
                    }
                }
            }

            $pdo->commit();

            $post->getTags($pdo);
            return $post;
        } catch (Exception | ServerException $exception) {
            $pdo->rollBack();

            throw $exception;
        }
    }
}
