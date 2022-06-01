<?php
namespace App\models;

use App\lib\ServerException;
use App\lib\PDOFactory;
use Exception;
use PDO;
use PDOException;

class Tag
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $created_at;

    private function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->created_at = $row['created_at'];
    }

    public static function getAllByPostId(string $post_id)
    {
        $pdo = PDOFactory::create();

        try {
            $statement = $pdo->prepare(
                'SELECT tags.*
                FROM posts
                INNER JOIN post_to_tags
                    ON posts.id = post_to_tags.post_id
                LEFT JOIN tags
                    ON post_to_tags.tag_id = tags.id
                WHERE posts.id = :post_id;
                '
            );
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->execute();
    
            $result = array_map(function ($row) {
                return new Tag($row);
            }, $statement->fetchAll());
    
            return $result;
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }
}
