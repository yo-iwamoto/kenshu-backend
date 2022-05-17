<?php
require_once dirname(__FILE__, 2) . '/lib/pdo.php';

class User
{
    public $name;
    public $profile_image_url;
    public $email;
    public $created_at;
    private $password_hash;

    private function __construct($row)
    {
        $this->name = $row['name'];
        $this->profile_image_url = $row['profile_image_url'];
        $this->email = $row['email'];
        $this->created_at = $row['created_at'];
        $this->password_hash = $row['password_hash'];
    }

    /**
     * @param array $data (name, email, password, profile_image_url)
     */
    public static function create($data)
    {
        $pdo = PDOFactory::create();
        try {
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash, profile_image_url, created_at) VALUES (?, ?, ?, ?, NOW())');
            $statement->bindParam(1, $data['name']);
            $statement->bindParam(2, $data['email']);
            $statement->bindParam(3, $password_hash);
            $statement->bindParam(4, $data['profile_image_url']);

            $statement->execute();
        } catch (PDOException $err) {
            if ($err->getCode() === '23505') {
                print('指定したメールアドレスは既に登録済みです。ログインしてください。');
            } else {
                print_r($err);
                throw Exception('failed adding data');
            }
        }
    }

    /**
     * @param string $email
     * @return User
     * TODO: 存在しない email で壊れる。
     */
    public static function get_by_email($email)
    {
        $pdo = PDOFactory::create();
        $result = $pdo->query("SELECT * FROM users WHERE email = '$email' LIMIT 1")->fetch();
        return new User($result);
    }

    /**
     * @param int $id
     * @return User
     * TODO: 存在しない id で壊れる。
     */
    public static function get_by_id($id)
    {
        $pdo = PDOFactory::create();
        $result = $pdo->query('SELECT * FROM users WHERE id = ? LIMIT 1', $id);
        return new User($result[0]);
    }
}
