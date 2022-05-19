<?php
namespace App\models;

use App\lib\PDOFactory;

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
        string $profile_image_url = '',
    ) {
        $pdo = PDOFactory::create();

        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash, profile_image_url, created_at) VALUES (?, ?, ?, ?, NOW())');
            $statement->bindParam(1, $name);
            $statement->bindParam(2, $email);
            $statement->bindParam(3, $password_hash);
            $statement->bindParam(4, $profile_image_url);

            $statement->execute();
        } catch (PDOException $err) {
            if ($err->getCode() === '23505') {
                // TODO: フィードバック
            } else {
                print_r($err);
                throw Exception('failed adding data');
            }
        }
    }

    /**
     * email の一致するレコードを取得し User で返す
     * @param string $email
     * @return User
     * TODO: 存在しない email で壊れる。
     */
    public static function get_by_email(string $email)
    {
        $pdo = PDOFactory::create();
        $result = $pdo->query("SELECT * FROM users WHERE email = '$email' LIMIT 1")->fetch();
        return new User($result);
    }

    /**
     * id の一致するレコードを取得し User で返す
     * @param string $id
     * @return User
     * TODO: 存在しない id で壊れる。
     */
    public static function get_by_id(string $id)
    {
        $pdo = PDOFactory::create();
        $result = $pdo->query('SELECT * FROM users WHERE id = ? LIMIT 1', $id)->fetch();
        return new User($result);
    }

    /**
     *  @param string $password
     *  @return bool 検証結果
     */
    public function login(string $password)
    {
        return password_verify($password, $this->password_hash);
    }
}
