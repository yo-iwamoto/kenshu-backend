<?php
namespace App\models;

use App\dto\CreateUserDto;
use App\lib\ServerException;

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
        $this->id = strval($row['id']);
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
    public static function create(PDO $pdo, CreateUserDto $dto)
    {
        self::validate($dto);

        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $statement = $pdo->prepare(
                'INSERT INTO users
                    (name, email, password_hash, profile_image_url, created_at)
                VALUES
                    (:name, :email, :password_hash, :profile_image_url, NOW())
                '
            );
            $statement->bindParam(':name', $dto->name);
            $statement->bindParam(':email', $dto->email);
            $statement->bindParam(':password_hash', $password_hash);
            $statement->bindParam(':profile_image_url', $dto->profile_image_url);

            $statement->execute();
        } catch (Exception | ServerException $exception) {
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
    public static function getByEmail(PDO $pdo, string $email): self
    {
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
    public static function getById(PDO $pdo, string $id): self
    {
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
    public static function validate(CreateUserDto $dto): void
    {
        $errors = [];

        if (strlen($dto->name) === 0) {
            array_push($errors, 'ユーザー名は必須項目です');
        }

        if (strlen($dto->name) > 100) {
            array_push($errors, 'ユーザー名は100文字以内で入力してください');
        }

        if (strlen($dto->password) === 0) {
            array_push($errors, 'パスワードは必須項目です') ;
        }

        if (strlen($dto->password) > 72) {
            array_push($errors, 'パスワードは72文字以内で入力してください');
        }

        // 現実的に妥当そうなメールアドレスの検証 (quoted-string などを含まない)
        /** @see https://developer.mozilla.org/ja/docs/Web/HTML/Element/input/email#%E5%9F%BA%E6%9C%AC%E7%9A%84%E3%81%AA%E6%A4%9C%E8%A8%BC */
        $valid_email_regex = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/";
        if (!preg_match($valid_email_regex, $dto->email)) {
            throw ServerException::invalidRequest(display_text: 'メールアドレスの形式が誤っています');
        }
        
        if (count($errors) !== 0) {
            throw ServerException::invalidRequest(display_text: join('<br />', $errors));
        }
    }
}
