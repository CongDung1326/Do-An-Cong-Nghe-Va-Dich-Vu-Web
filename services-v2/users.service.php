<?php
require_once __DIR__ . "/../config.php";

class Users
{
    private $db_users, $err_code = 0;
    public function __construct()
    {
        $this->db_users = new UsersDB();
    }
    public function login($data)
    {
        if (!isset($data->username) && !isset($data->password)) return ["err_code" => $this->err_code = 3];
        $username = $data->username;
        $password = $data->password;

        $result = $this->db_users->exec_login($username, $password);
        switch ($result) {
            case 0:
                $data = $this->db_users->exec_find_login_by_username_password($username, $password);
                return ["err_code" => $this->err_code, "data" => $data];
            case 1:
                return ["err_code" => $this->err_code = 1];
        }
    }
}
