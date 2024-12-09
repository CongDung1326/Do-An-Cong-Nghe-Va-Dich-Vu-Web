<?php
require_once __DIR__ . "/../config.php";

class Images
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }
    public function GetAllImagesRankLOL()
    {
        $query = "SELECT * FROM images WHERE type='rank_lol'";
        $images = $this->db->get_list($query);

        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "images" => $images
            ]);
        } else {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Hình ảnh đang trống không",
                "images" => []
            ]);
        }
    }
}
