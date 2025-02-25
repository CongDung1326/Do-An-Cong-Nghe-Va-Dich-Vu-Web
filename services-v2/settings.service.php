<?php
require_once __DIR__ . "/../config.php";

class Settings
{
    private $db_settings, $err_code = 0;
    public function __construct()
    {
        $this->db_settings = new SettingsDB();
    }
    public function dis_connect()
    {
        $this->db_settings->dis_connect();
    }
    public function site($key_code)
    {
        $result = $this->db_settings->exec_get_all_setting($key_code);
        if ($result == null) return ["err_code" => $this->err_code = 4];
        return ["err_code" => $this->err_code, "data" => $result];
    }
}
