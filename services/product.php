<?php
require_once __DIR__ . "/../config.php";

class Product
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }
    public function GetAllProduct()
    {
        $table = "store_account_children";
        $query = "SELECT * FROM $table";
        $products = $this->db->get_list($query);

        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "products" => $products
            ]);
        } else {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Sản phẩm đang trống không",
                "products" => []
            ]);
        }
    }
    public function GetAllProductByIdCategory($id_category)
    {
        $table = "store_account_children";
        $table_category = "store_account_parent";
        $query = "SELECT * FROM $table WHERE store_account_parent_id=$id_category";
        $query_category = "SELECT * FROM $table_category WHERE id=$id_category";

        if (empty($id_category)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_category)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "id vui lòng phải là số"]);
        if ($this->db->num_rows($query_category) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy thể loại nào cả"]);

        $products = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "products" => $products
            ]);
        } else {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Sản phẩm đang trống không",
                "products" => []
            ]);
        }
    }
}
