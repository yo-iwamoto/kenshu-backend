<?php
namespace App\models;

use App\lib\ServerException;

use Exception;
use PDO;
use PDOException;

class PostImage
{
    public readonly string $post_id;
    public readonly string $image_url;

    /**
     * @return string 作成されたレコードのid
     */
    public static function create(PDO $pdo, string $post_id, string $image_url): string
    {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO post_images
                    (post_id, image_url, created_at)
                VALUES
                    (:post_id, :image_url, NOW())
                RETURNING id
                '
            );
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->bindParam(':image_url', $image_url, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch()['id'];
        } catch (Exception | ServerException $exception) {
            var_dump($exception);
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public static function destroy(PDO $pdo, string $id)
    {
        try {
            $statement = $pdo->prepare('DELETE FROM post_images WHERE id = :id');
    
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (Exception | ServerException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }
}
