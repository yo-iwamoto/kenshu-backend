<?php
namespace App\models;

use App\dto\CreatePostDto;
use App\dto\UpdatePostDto;
use App\lib\ServerException;

use PDO;
use PDOException;
use Exception;

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
        $this->id = strval($row['id']);
        // thumbnail_url は nullable のため、null のとき専用の画像の URL を指定する
        $this->thumbnail_url = $row['thumbnail_url'] ?? self::NO_IMAGE_URL;
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->created_at = $row['created_at'];

        // TODO: User で管理する
        $this->user__name = $row['user__name'];
        $this->user__profile_image_url = $row['user__profile_image_url'];
    }

    /**
     * @return string INSERT されたレコードの `id`
     * INSERT 後、id を返す (User を作るには JOIN が必要なので id だけ)
     */
    public static function create(PDO $pdo, CreatePostDto $dto): string
    {
        self::validate($dto);

        try {
            $statement = $pdo->prepare(
                'INSERT INTO posts
                    (user_id, title, content, created_at)
                VALUES
                    (:user_id, :title, :content, NOW())
                RETURNING *
                '
            );
            $statement->bindParam(':user_id', $dto->user_id, PDO::PARAM_INT);
            $statement->bindParam(':title', $dto->title);
            $statement->bindParam(':content', $dto->content);

            $statement->execute();

            $created_post_id = $statement->fetch()['id'];
            
            return $created_post_id;
        } catch (Exception | PDOException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    public static function getAll(PDO $pdo)
    {
        try {
            $statement = $pdo->query(
                'SELECT
                    posts.*,
                    post_images.image_url AS thumbnail_post_image_url,
                    users.name AS user__name,
                    users.profile_image_url AS user__profile_image_url
                FROM posts
                    INNER JOIN users ON posts.user_id = users.id
                    LEFT JOIN post_images ON post_images.id = posts.thumbnail_post_image_id
                ORDER BY created_at DESC
                '
            );

            $result = array_map(fn ($row) => new self($row), $statement->fetchAll());

            return $result;
        } catch (Exception | PDOException $exception) {
            var_dump('hello');
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    /**
     * @throws ServerException
     */
    public static function getById(PDO $pdo, string $id): self
    {
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
                LIMIT 1
                '
            );
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            $result = $statement->fetch();

            if (!$result) {
                throw  ServerException::noSuchRecord();
            }
            return new self($result);
        } catch (Exception | PDOException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    /**
     * @throws ServerException
     */
    public function update(PDO $pdo, UpdatePostDto $dto)
    {
        self::validate($dto);

        $post_id = $this->id;

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
            $statement->bindParam(':title', $dto->title);
            $statement->bindParam(':content', $dto->content);
            $statement->execute();
        } catch (Exception | PDOException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    /**
     * @throws ServerException
     */
    public function destroy(PDO $pdo): void
    {
        $post_id = $this->id;
        
        try {
            $statement = $pdo->prepare('DELETE FROM posts WHERE id = :id');
            $statement->bindParam(':id', $post_id, PDO::PARAM_INT);
            $statement->execute();
        } catch (Exception | PDOException $exception) {
            if ($exception instanceof PDOException) {
                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    /**
     * getAll などで一般化して JOIN できなかったためやむなくの実装;
     * 複数件取得時に叩くと容易に N+1 になるので注意
     * @throws ServerException
     */
    public function getTags(PDO $pdo)
    {
        $this->tags = Tag::getAllByPostId($pdo, $this->id);
    }

    /**
     * @param array $data クライアントからの入力
     * @return void
     * @throws SererException
     */
    public static function validate(CreatePostDto|UpdatePostDto $dto): void
    {
        $errors = [];

        if (strlen($dto->title) === 0) {
            array_push($errors, 'タイトルは必須項目です');
        }

        if (strlen($dto->title) > 100) {
            array_push($errors, 'タイトルは100文字以内で入力してください');
        }

        if (strlen($dto->content) === 0) {
            array_push($errors, '本文は必須項目です') ;
        }

        if (count($errors) !== 0) {
            throw ServerException::invalidRequest(display_text: join('<br />', $errors));
        }
    }
}
