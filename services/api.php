<?php
require_once __DIR__ . "/../config.php";

class Api
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }
    protected function CheckIsAdmin($username, $password)
    {
        $username = check_string($username);
        $password = check_string($password);
        $table = "api";
        $query = "SELECT * FROM $table WHERE username = '$username' AND password = '$password'";

        if ($this->db->num_rows($query) == 0) return false;
        return true;
    }
}
