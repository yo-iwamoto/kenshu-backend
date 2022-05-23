<?php
namespace App\models;

use App\lib\PDOFactory;

class Post
{
    const NO_IMAGE_URL = 'assets/img/no-img.jpeg';
    
    public readonly string $id;
    public readonly string  $user_id;
    public readonly string  $user_name;
    public readonly string $thumbnail_url;
    public readonly string $title;
    public readonly string $content;
    public readonly string $created_at;

    private function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->user_id = $row['user_id'];
        // thumbnail_url は nullable のため、null のとき専用の画像の URL を指定する
        $this->thumbnail_url = $row['thumbnail_url'] ?? self::NO_IMAGE_URL;
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->created_at = $row['created_at'];
    }

    /**
     * @return array(User)
     */
    public static function getAll()
    {
        $pdo = PDOFactory::create();

        $result = [];
        $statement = $pdo->query('SELECT posts.*, users.name AS user_name, post_images.image_url AS thumbnail_post_image_url FROM posts INNER JOIN users ON posts.user_id = users.id LEFT JOIN post_images ON post_images.id = posts.thumbnail_post_image_id;');

        while ($row = $statement->fetch()) {
            array_push($result, new Post($row));
        }

        return $result;
    }

    public static function getById(string $id)
    {
        $pdo = PDOFactory::create();

        $statement = $pdo->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
        $statement->bindParam(1, $id);
        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            throw new Exception('no such post');
        }
        return new Post($result);
    }
}
