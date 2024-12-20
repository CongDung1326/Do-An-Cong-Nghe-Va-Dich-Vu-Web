<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class Settings extends Api
{
    private $db, $api;
    public function __construct()
    {
        $this->db = new DB();
        $this->api = new Api();
    }
    public function GetAllSettings()
    {
        $table = "settings";
        $query = "SELECT * FROM $table";

        $settings = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "settings" => $settings
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Phần cài đặt đang trống vui lòng nhập thêm",
                "settings" => []
            ]);
        }
    }
}
