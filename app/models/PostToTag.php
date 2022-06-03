<?php
namespace App\models;

use App\lib\ServerException;

use Exception;
use PDO;
use PDOException;

class PostToTag
{
    public readonly string $post_id;
    public readonly string $tag_id;

    public static function create(PDO $pdo, string $post_id, string $tag_id)
    {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO post_to_tags
                    (post_id, tag_id, created_at)
                VALUES
                    (:post_id, :tag_id, NOW())
                '
            );
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
            $statement->execute();
        } catch (Exception | ServerException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public static function destroy(PDO $pdo, string $post_id, string $tag_id)
    {
        try {
            $statement = $pdo->prepare(
                'DELETE FROM post_to_tags
                WHERE post_id = :post_id
                AND tag_id = :tag_id
                '
            );
    
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
            $statement->execute();
        } catch (Exception | ServerException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }
}
