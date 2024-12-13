<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class Product extends Api
{
    private $db, $api;
    public function __construct()
    {
        $this->db = new DB();
        $this->api = new Api();
    }
    public function GetAllProduct()
    {
        $table = "store_account_children";
        $table_category = "store_account_parent";
        $query = "SELECT p.id, p.title, p.comment, p.store, p.sold, p.price, p.time_created, p.store_account_parent_id, c.name FROM $table p, $table_category c WHERE c.id = p.store_account_parent_id";
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
    public function AddProduct($title, $comment, $price, $id_category, $data)
    {
        $title = check_string($title);
        $comment = check_string($comment);
        $table = "store_account_children";
        $table_category = "store_account_parent";
        $query = "SELECT * FROM $table WHERE title='$title'";
        $query_category = "SELECT * FROM $table_category WHERE id=$id_category";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($title) || empty($comment) || empty($price) || empty($id_category)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_category) == 0)  return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy loại của sản phẩm"]);
        if ($this->db->num_rows($query) > 0)  return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Trùng tên sản phẩm"]);
        if (!is_numeric($price) && $price > 0)  return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Giá tiền vui lòng phải lớn hơn không và phải là số"]);
        if (!is_numeric($id_category))  return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Id vui lòng phải là số"]);

        $this->db->insert($table, [
            "title" => $title,
            "comment" => $comment,
            "price" => $price,
            "store_account_parent_id" => $id_category,
        ]);

        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Thêm sản phẩm thành công",
        ]);
    }
    public function EditProduct($title, $comment, $price, $id_category, $id_product, $data)
    {
        $title = check_string($title);
        $comment = check_string($comment);
        $table = "store_account_children";
        $table_category = "store_account_parent";
        $query = "SELECT * FROM $table WHERE title='$title'";
        $query_category = "SELECT * FROM $table_category WHERE id=$id_category";
        $query_check_product = "SELECT * FROM $table WHERE id=$id_product";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($title) || empty($comment) || empty($price) || empty($id_category) || empty($id_product)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_category) == 0)  return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy loại của sản phẩm"]);
        if ($this->db->num_rows($query) > 1)  return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Trùng tên sản phẩm"]);
        if (!is_numeric($price) && $price > 0)  return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Giá tiền vui lòng phải lớn hơn không và phải là số"]);
        if (!is_numeric($id_product) || !is_numeric($id_category))  return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Id vui lòng phải là số"]);
        if ($this->db->num_rows($query_check_product) == 0)  return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Không tìm thấy sản phẩm"]);

        $this->db->update($table, [
            "title" => $title,
            "comment" => $comment,
            "price" => $price,
            "store_account_parent_id" => $id_category,
        ], "id=$id_product");

        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Thêm sản phẩm thành công",
        ]);
    }
    public function GetProductById($id_product, $data)
    {
        $table = "store_account_children";
        $query = "SELECT * FROM $table WHERE id=$id_product";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_product)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_product))  return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Id vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0)  return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy sản phẩm"]);

        $product = $this->db->get_row($query);
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "product" => $product
        ]);
    }
    public function RemoveProduct($id_product, $data)
    {
        $table = "store_account_children";
        $query = "SELECT * FROM $table WHERE id=$id_product";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_product)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_product))  return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Id vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0)  return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy sản phẩm"]);

        $this->db->remove($table, "id=$id_product");
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xoá sản phẩm thành công",
        ]);
    }
    public function BuyItemProduct($id_product, $id_user, $amount, $data)
    {
        $table = "store_account_children";
        $table_user = "user";
        $table_notification = "notification_buy";
        $table_account = "account";

        $query = "SELECT * FROM $table WHERE id=$id_product";
        $query_user = "SELECT * FROM $table_user WHERE id=$id_user";
        $random = random_string();

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_product) || empty($id_user) || empty($amount)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_product) || !is_numeric($id_user)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "id vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy sản phẩm"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Không tìm thấy người dùng"]);
        if (!is_numeric($amount) && $amount <= 0)  return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Số lượng vui lòng phải là số và phải lớn hơn 0"]);

        $user = $this->db->get_row($query_user);
        $product = $this->db->get_row($query);
        $price = $product['price'];
        $store = $product['store'];
        $sold = $product['sold'];
        $money = $user['money'];
        $total_buyed = $store - $amount;
        $total_money = $amount * $price;
        $total_sold = $sold + $amount;
        $deduct_amount = $money - $total_money;

        if ($total_buyed < 0) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Số lượng hàng đang không có đủ"]);
        if ($money < $total_money) return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Tiền không đủ vui lòng nạp thêm"]);

        $this->db->update($table, [
            "store" => $total_buyed,
            "sold" => $total_sold
        ], "id=$id_product");
        $this->db->update($table_user, [
            "money" => $deduct_amount
        ], "id=$id_user");
        $this->db->insert($table_notification, [
            "amount" => $amount,
            "user_id" => $id_user,
            "store_account_children_id" => $id_product,
            "money" => $total_money,
            "unique_code" => $random,
            "time" => time(),
            "is_show" => "T"
        ]);
        $this->db->update($table_account, [
            "is_sold" => "T",
            "user_id" => $id_user,
            "unique_code" => $random
        ], "store_account_children_id = $id_product AND is_sold='F' LIMIT $amount");

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Mua sản phẩm thành công",
        ]);
    }
}
