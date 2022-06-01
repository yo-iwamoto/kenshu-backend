<?php
namespace App\models;

use App\lib\PDOFactory;
use App\lib\ModelException;
use App\lib\ServerException;
use Exception;
use PDO;
use PDOException;

class Post
{
    const NO_IMAGE_URL = 'assets/img/no-img.jpeg';
    
    public readonly string $id;
    public readonly string $thumbnail_url;
    public readonly string $title;
    public readonly string $content;
    public readonly string $created_at;

    // TODO: User で管理する
    public readonly string $user__name;
    public readonly string $user__profile_image_url;

    // tag がない [] の状態と区別するため null
    public ?array $tags = null;

    private function __construct(array $row)
    {
        $this->id = $row['id'];
        // thumbnail_url は nullable のため、null のとき専用の画像の URL を指定する
        $this->thumbnail_url = $row['thumbnail_url'] ?? self::NO_IMAGE_URL;
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->created_at = $row['created_at'];

        // TODO: User で管理する
        $this->user__name = $row['user__name'];
        $this->user__profile_image_url = $row['user__profile_image_url'];
    }

    public static function create(
        string $user_id,
        string $title,
        string $content,
    ) {
        self::validate($title, $content);
        
        $pdo = PDOFactory::create();
        
        try {
            $statement = $pdo->prepare(
                'INSERT INTO posts
                    (user_id, title, content, created_at)
                VALUES
                    (:user_id, :title, :content, NOW())
                RETURNING *;
                '
            );
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $statement->bindParam(':title', $title);
            $statement->bindParam(':content', $content);
    
            $statement->execute();

            return $statement->fetch()['id'];
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public static function getAll()
    {
        $pdo = PDOFactory::create();

        try {
            $statement = $pdo->query(
                'SELECT
                    posts.*,
                    users.name AS user__name,
                    users.profile_image_url AS user__profile_image_url,
                    post_images.image_url AS thumbnail_post_image_url
                FROM posts
                    INNER JOIN users ON posts.user_id = users.id
                    LEFT JOIN post_images ON post_images.id = posts.thumbnail_post_image_id
                ORDER BY created_at DESC;
            '
            );
    
            $result =  array_map(function ($row) {
                return new Post($row);
            }, $statement->fetchAll());
    
            return $result;
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public static function getById(string $id)
    {
        $pdo = PDOFactory::create();

        try {
            $statement = $pdo->prepare(
                'SELECT
                    posts.*,
                    users.name AS user__name,
                    users.profile_image_url AS user__profile_image_url,
                    post_images.image_url AS thumbnail_post_image_url
                FROM posts
                    INNER JOIN users ON posts.user_id = users.id
                    LEFT JOIN post_images ON post_images.id = posts.id
                WHERE posts.id = :id
                LIMIT 1;
            '
            );
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
    
            $result = $statement->fetch();
    
            if (!$result) {
                throw  ModelException::noSuchRecord();
            }
            return new Post($result);
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public function update(string $title, string $content)
    {
        self::validate($title, $content);
        
        $post_id = $this->id;
        
        $pdo = PDOFactory::create();

        try {
            $statement = $pdo->prepare(
                'UPDATE posts
            SET
                title = :title,
                content = :content
            WHERE id = :id
            '
            );
            $statement->bindParam(':id', $post_id, PDO::PARAM_INT);
            $statement->bindParam(':title', $title);
            $statement->bindParam(':content', $content);
            $statement->execute();
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public function destroy()
    {
        $post_id = $this->id;
        
        $pdo = PDOFactory::create();

        try {
            $statement = $pdo->prepare('DELETE FROM posts WHERE id = :id');
            $statement->bindParam(':id', $post_id, PDO::PARAM_INT);
            $statement->execute();
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public function getTags()
    {
        $this->tags = Tag::getAllByPostId($this->id);
    }

    /**
     * @param array $data クライアントからの入力
     * @return void
     * 不正なとき例外を投げる
     */
    public static function validate(
        string $title,
        string $content,
    ): void {
        $errors = [];

        if (strlen($title) === 0) {
            array_push($errors, 'タイトルは必須項目です');
        }

        if (strlen($title) > 100) {
            array_push($errors, 'タイトルは100文字以内で入力してください');
        }

        if (strlen($content) === 0) {
            array_push($errors, '本文は必須項目です') ;
        }

        if (count($errors) !== 0) {
            throw ServerException::invalidRequest(display_text: join('<br />', $errors));
        }
    }
}
