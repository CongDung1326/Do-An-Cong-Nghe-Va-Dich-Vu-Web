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
    public function GetCategoryById($id_category)
    {
        $table = "store_account_parent";
        $query = "SELECT * FROM $table WHERE id=$id_category";

        if (empty($id_category)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_category)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Id vui lòng là số"]);
        if ($this->db->num_rows($query) == 0)  return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy danh mục nào"]);

        $category = $this->db->get_row($query);
        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "category" => $category
        ]);
    }
    public function AddCategory($name)
    {
        $table = "store_account_parent";
        $name = check_string($name);
        $query = "SELECT * FROM $table WHERE name='$name'";

        if (empty($name)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query) > 0) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Tên đã được sử dụng"]);

        $this->db->insert($table, [
            "name" => $name
        ]);
        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Thêm thành công",
        ]);
    }
    public function EditCategory($id_category, $name)
    {
        $table = "store_account_parent";
        $name = check_string($name);
        $query = "SELECT * FROM $table WHERE id=$id_category";
        $query_name_exist = "SELECT * FROM $table WHERE name='$name'";

        if (empty($name) || empty($id_category)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_category)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Id vui lòng là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy chuyên mục"]);
        if ($this->db->num_rows($query_name_exist) > 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Tên đã được sử dụng"]);

        $this->db->update($table, [
            "name" => $name
        ], "id=$id_category");
        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Sửa thành công",
        ]);
    }
    public function RemoveCategory($id_category)
    {
        $table = "store_account_parent";
        $query = "SELECT * FROM $table WHERE id=$id_category";

        if (empty($id_category)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_category)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Id vui lòng là số"]);
        if ($this->db->num_rows($query) == 0)  return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy danh mục nào"]);

        $this->db->remove($table, "id=$id_category");
        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xoá dữ liệu thành công",
        ]);
    }
}
