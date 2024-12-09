<?php
require_once __DIR__ . "/../config.php";

class Category
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }
    public function GetAllCategory()
    {
        $table = "store_account_parent";
        $query = "SELECT * FROM $table";

        $categories = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "categories" => $categories
            ]);
        } else {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Dữ liệu đang trống",
                "categories" => []
            ]);
        }
    }
}
