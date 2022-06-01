<?php
namespace App\models;

use App\lib\ServerException;
use App\lib\PDOFactory;
use Exception;
use PDO;
use PDOException;

class User
{
    public readonly string $id;
    public readonly string  $name;
    public readonly ?string $profile_image_url;
    public readonly string $email;
    public readonly string $created_at;
    private string $password_hash;

    private function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->profile_image_url = $row['profile_image_url'];
        $this->email = $row['email'];
        $this->created_at = $row['created_at'];
        $this->password_hash = $row['password_hash'];
    }

    /**
     * データからレコードを作成する
     * @param string $name
     * @param string $email
     * @param string $password
     * @param ?string $profile_image_url
     */
    public static function create(
        string $name,
        string $email,
        string $password,
        string $profile_image_url,
    ) {
        self::validate($name, $email, $password);
        
        $pdo = PDOFactory::create();

        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $statement = $pdo->prepare(
                'INSERT INTO users
                    (name, email, password_hash, profile_image_url, created_at)
                VALUES
                    (:name, :email, :password_hash, :profile_image_url, NOW())
                '
            );
            $statement->bindParam(':name', $name);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password_hash', $password_hash);
            $statement->bindParam(':profile_image_url', $profile_image_url);

            $statement->execute();
        } catch (Exception $exception) {
            if ($exception instanceof PDOException) {
                if ($exception->getCode() === '23505') {
                    throw ServerException::emailAlreadyExists($exception);
                }

                throw ServerException::database($exception);
            }

            throw ServerException::internal($exception);
        }
    }

    /**
     * email の一致するレコードを取得し User で返す
     * @param string $email
     * @return User
     */
    public static function getByEmail(string $email): self
    {
        $pdo = PDOFactory::create();
        $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->bindParam(':email', $email);

        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            throw ServerException::noSuchRecord();
        }
        return new self($result);
    }

    /**
     * id の一致するレコードを取得し User で返す
     * @param string $id
     * @return User
     */
    public static function getById(string $id): self
    {
        $pdo = PDOFactory::create();
        $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            throw ServerException::noSuchRecord();
        }
        return new self($result);
    }

    /**
     *  @param string $password
     *  @return bool 検証結果
     */
    public function login(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }

    /**
     * @param array $data クライアントからの入力
     * @return void
     * 不正なとき例外を投げる
     */
    public static function validate(
        string $name,
        string $email,
        string $password,
    ): void {
        $errors = [];

        if (strlen($name) === 0) {
            array_push($errors, 'ユーザー名は必須項目です');
        }

        if (strlen($name) > 100) {
            array_push($errors, 'ユーザー名は100文字以内で入力してください');
        }

        if (strlen($password) === 0) {
            array_push($errors, 'パスワードは必須項目です') ;
        }

        if (strlen($password) > 72) {
            array_push($errors, 'ユーザー名は72文字以内で入力してください');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, 'メールアドレスの形式が誤っています');
        }

        if (count($errors) !== 0) {
            throw ServerException::invalidRequest(display_text: join('<br />', $errors));
        }
    }
}
