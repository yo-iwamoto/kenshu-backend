<?php
namespace App\services\post;

use App\dto\UpdatePostDto;
use App\lib\Request;
use App\lib\ServerException;
use App\models\Post;
use App\models\PostImage;
use App\models\PostToTag;
use App\services\common\ValidateUploadedImageService;
use App\services\concerns\Service;

use Exception;
use PDO;
use Ramsey\Uuid\Nonstandard\Uuid;

class UpdateService extends Service
{
    public function execute(Request $request, string $id)
    {
        $pdo = $this->pdo;

        $current_user_id = $request->getCurrentUserId();

        try {
            $dto = new UpdatePostDto(
                user_id: $current_user_id,
                title: $request->post['title'],
                content: $request->post['content'],
                tags: isset($request->post['tags']) ? $request->post['tags'] : [],
            );

            $pdo->beginTransaction();

            $post = Post::getById($pdo, $id);
            $post->update($pdo, $dto);
            $post->getTags($pdo);

            $current_tag_ids = [];

            // TODO: 以下、計算量が本当に酷いので修正
            foreach ($post->tags as $tag) {
                if (!in_array($tag->id, $dto->tags)) {
                    PostToTag::destroy($pdo, $id, $tag->id);
                } else {
                    array_push($current_tag_ids, $tag->id);
                }
            }
            // 指定された tag_ids のうち、現在存在していないものを追加
            foreach (array_diff($dto->tags, $current_tag_ids) as $tag_id) {
                PostToTag::create($pdo, $id, $tag_id);
            }

            $current_post_image_urls = [];
            if (isset($request->files['images'])) {
                // 現在の画像のパスをデータ削除前に取得しておき、トランザクションのコミット後に削除を試行
                // (トランザクションをロールバックした場合、画像削除はロールバックできないため)
                $current_post_image_urls = array_map(fn ($post_image) => $post_image->image_url, PostImage::getAllByPostId($pdo, $id));
                
                // サムネイルを NULL に更新
                $post->updateThumbnailPostImageId($pdo, null);
                // DB から PostImage を削除
                PostImage::bulkDestroyByPostId($pdo, $id);

                // 画像の作成
                $uploaded_images = $request->files['images'];
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
    
                    $post_image_id = PostImage::create($pdo, $id, $file_path);
                    // 画像の保存
                    move_uploaded_file($file['tmp_name'], '../public' . $file_path);
    
                    if ($index === intval($request->post['thumbnail_image_index'])) {
                        $post->updateThumbnailPostImageId($pdo, $post_image_id);
                    }
                }
            }


            $pdo->commit();

            // 変更前の画像ファイルの削除
            foreach ($current_post_image_urls as $url) {
                unlink('../public' . $url);
            }

            $post->getTags($pdo);

            return $post;
        } catch (Exception | ServerException $exception) {
            $pdo->rollBack();

            throw $exception;
        }
    }
}
