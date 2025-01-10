<?php
require_once __DIR__ . "/../config.php";

class Category
{
    private $db_category, $err_code = 0;
    public function __construct()
    {
        $this->db_category = new CategoryDB();
    }
    public function GetAllCategory()
    {
        $categories = $this->db_category->exec_select_all(null, null);
        $this->db_category->dis_connect();
        if (count($categories) > 0)
            return ["err_code" => $this->err_code, "data" => $categories];
        else
            return ["err_code" => $this->err_code = 22, "data" => []];
    }
    public function GetCategoryById($id_category)
    {
        $where = "id=$id_category";
        if (empty($id_category)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_category)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_category->check_category_exist($id_category)) return ["err_code" => $this->err_code = 23];

        $data = $this->db_category->exec_select_one("", $where);
        $this->db_category->dis_connect();
        return [
            "err_code" => $this->err_code,
            "data" => $data
        ];
    }
    public function AddCategory($name)
    {
        $name = check_string($name);

        if (empty($name))  return ["err_code" => $this->err_code = 1];
        if ($this->db_category->check_category_name_exist($name) > 0)  return ["err_code" => $this->err_code = 24];

        $this->db_category->exec_insert([
            "name" => $name
        ]);
        $this->db_category->dis_connect();
        return ["err_code" => $this->err_code];
    }
    public function EditCategory($id_category, $name)
    {
        $name = check_string($name);
        $where_exist_category = "id=$id_category";

        if (empty($name) || empty($id_category)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_category)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_category->check_category_exist($id_category)) return ["err_code" => $this->err_code = 25];
        if ($this->db_category->check_category_name_exist($name) > 0) return ["err_code" => $this->err_code = 24];

        $this->db_category->exec_update([
            "name" => $name
        ], $where_exist_category);
        $this->db_category->dis_connect();
        return ["err_code" => $this->err_code];
    }
    public function RemoveCategory($id_category)
    {
        $where = "id=$id_category";

        if (empty($id_category)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_category)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_category->check_category_exist($id_category)) return ["err_code" => $this->err_code = 25];

        $this->db_category->exec_remove($where);
        $this->db_category->dis_connect();
        return ["err_code" => $this->err_code];
    }
}
