<?php
require_once __DIR__ . "/../config.php";

class Api
{
    private $db_api, $err_code = 0;
    public function __construct()
    {
        $this->db_api = new ApiDB();
    }
    public function CheckIsAdmin($username, $password)
    {
        $username = check_string($username);
        $password = hash_encode(check_string($password));

        if ($this->db_api->exec_num_rows("username = '$username' AND password = '$password'") == 0) return false;
        return true;
    }
}
