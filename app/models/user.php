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
        $result = $pdo->query('SELECT * FROM users WHERE id = ? LIMIT 1', $id);
        return new User($result[0]);
    }
}
