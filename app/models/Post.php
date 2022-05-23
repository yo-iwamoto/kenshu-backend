<?php
namespace App\models;

use App\lib\PDOFactory;
use PDOException;

class Post
{
    const NO_IMAGE_URL = 'assets/img/no-img.jpeg';
    
    public readonly string $id;
    public readonly string  $user_id;
    public readonly string  $user_name;
    public readonly string  $user_profile_image_url;
    public readonly string $thumbnail_url;
    public readonly string $title;
    public readonly string $content;
    public readonly string $created_at;

    private function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->user_id = $row['user_id'];
        $this->user_name = $row['user_name'];
        $this->user_profile_image_url = $row['user_profile_image_url'];
        // thumbnail_url は nullable のため、null のとき専用の画像の URL を指定する
        $this->thumbnail_url = $row['thumbnail_url'] ?? self::NO_IMAGE_URL;
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->created_at = $row['created_at'];
    }

    public static function create(
        string $user_id,
        string $title,
        string $content,
    ) {
        try {
            $pdo = PDOFactory::create();
                
            $statement = $pdo->prepare('INSERT INTO posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())');
            $statement->bindParam(1, $user_id);
            $statement->bindParam(2, $title);
            $statement->bindParam(3, $content);
    
            $statement->execute();
        } catch (PDOException $err) {
            print_r($err);
            throw Exception('failed adding data');
        }
    }

    public static function getAll()
    {
        $pdo = PDOFactory::create();

        $result = [];
        $statement = $pdo->query(
            'SELECT
                posts.*,
                users.name AS user_name,
                users.profile_image_url AS user_profile_image_url,
                post_images.image_url AS thumbnail_post_image_url
            FROM posts
                INNER JOIN users ON posts.user_id = users.id
                LEFT JOIN post_images ON post_images.id = posts.thumbnail_post_image_id
            ORDER BY created_at DESC;
        '
        );

        while ($row = $statement->fetch()) {
            array_push($result, new Post($row));
        }

        return $result;
    }

    public static function getById(string $id)
    {
        $pdo = PDOFactory::create();
        $statement = $pdo->prepare(
            'SELECT
                posts.*,
                users.name AS user_name,
                users.profile_image_url AS user_profile_image_url,
                post_images.image_url AS thumbnail_post_image_url
            FROM posts
                INNER JOIN users ON posts.user_id = users.id
                LEFT JOIN post_images ON post_images.id = posts.id
            WHERE posts.id = ?
            LIMIT 1;
        '
        );
        $statement->bindParam(1, $id);
        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            throw new Exception('no such post');
        }
        return new Post($result);
    }
}
