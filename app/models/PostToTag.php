<?php
namespace App\models;

use App\lib\PDOFactory;
use PDO;

class PostToTag
{
    public readonly string $post_id;
    public readonly string $tag_id;

    public static function create(string $post_id, string $tag_id)
    {
        $pdo = PDOFactory::create();

        $statement = $pdo->prepare(
            'INSERT INTO post_to_tags
                (post_id, tag_id, created_at)
            VALUES
                (:post_id, :tag_id, NOW());'
        );
        $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
        $statement->execute();
    }

    public static function destroy(string $post_id, string $tag_id)
    {
        $pdo = PDOFactory::create();

        $statement = $pdo->prepare(
            'DELETE FROM post_to_tags
            WHERE post_id = :post_id
            AND tag_id = :tag_id;
            '
        );

        $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
        $statement->execute();
    }
}
