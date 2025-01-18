<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class Product
{
    private $db, $db_product, $db_category, $db_user, $db_notification, $db_account, $err_code = 0;
    public function __construct()
    {
        $this->db = new DB();
        $this->db_product = new ProductDB();
        $this->db_category = new CategoryDB();
        $this->db_user = new UserDB();
        $this->db_notification = new NotificationDB();
        $this->db_account = new AccountDB();
    }
    public function GetAllProduct()
    {
        $products = $this->db_product->exec_get_all_product();

        $this->db_product->dis_connect();
        if (count($products) > 0) {
            return ["err_code" => $this->err_code, "data" => $products];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function GetAllProductByIdCategory($id_category)
    {

        if (empty($id_category)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_category)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_category->check_category_exist($id_category)) return ["err_code" => $this->err_code = 23];

        $products = $this->db_product->exec_select_all("", "store_account_parent_id=$id_category");
        $this->db_product->dis_connect();
        if (count($products) > 0) {
            return ["err_code" => $this->err_code, "data" => $products];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function AddProduct($title, $comment, $price, $id_category)
    {
        $title = check_string($title);
        $comment = check_string($comment);

        if (empty($title) || empty($comment) || empty($price) || empty($id_category)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_category->check_category_exist($id_category))  return ["err_code" => $this->err_code = 23];
        if ($this->db_product->check_title_exist($title)) return ["err_code" => $this->err_code = 44];
        if (!is_numeric($price) && $price > 0)  return ["err_code" => $this->err_code = 20];
        if (!is_numeric($id_category))  return ["err_code" => $this->err_code = 9];

        $this->db_product->exec_insert([
            "title" => $title,
            "comment" => $comment,
            "price" => $price,
            "store_account_parent_id" => $id_category,
        ]);
        $this->db_product->dis_connect();
        return ["err_code" => $this->err_code];
    }
    public function EditProduct($title, $comment, $price, $id_category, $id_product)
    {
        $title = check_string($title);
        $comment = check_string($comment);

        if (empty($title) || empty($comment) || empty($price) || empty($id_category) || empty($id_product)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_category->check_category_exist($id_category))  return ["err_code" => $this->err_code = 23];
        if ($this->db_product->check_title_bigger_one_exist($title))  return ["err_code" => $this->err_code = 44];
        if (!is_numeric($price) && $price > 0)  return ["err_code" => $this->err_code = 20];
        if (!is_numeric($id_product) || !is_numeric($id_category))  return ["err_code" => $this->err_code = 9];
        if (!$this->db_product->check_product_exist($id_product))  return ["err_code" => $this->err_code = 31];

        $this->db_product->exec_update([
            "title" => $title,
            "comment" => $comment,
            "price" => $price,
            "store_account_parent_id" => $id_category,
        ], "id=$id_product");
        $this->db_product->dis_connect();
        return ["err_code" => $this->err_code];
    }
    public function GetProductById($id_product)
    {
        if (empty($id_product)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_product))  return ["err_code" => $this->err_code = 9];
        if (!$this->db_product->check_product_exist($id_product))  return ["err_code" => $this->err_code = 31];

        $product = $this->db_product->exec_select_one("", "id=$id_product");
        $this->db_product->dis_connect();
        return ["err_code" => $this->err_code, "data" => $product];
    }
    public function RemoveProduct($id_product)
    {

        if (empty($id_product)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_product))  return ["err_code" => $this->err_code = 9];
        if (!$this->db_product->check_product_exist($id_product))  return ["err_code" => $this->err_code = 31];

        $this->db_product->exec_remove("id=$id_product");
        $this->db_product->dis_connect();
        return ["err_code" => $this->err_code];
    }
    public function BuyItemProduct($id_product, $id_user, $amount)
    {
        $random = random_string();

        if (empty($id_product) || empty($id_user) || empty($amount)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_product) || !is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_product->check_product_exist($id_product))  return ["err_code" => $this->err_code = 31];
        if (!$this->db_user->check_user_exist($id_user))  return ["err_code" => $this->err_code = 10];
        if (!is_numeric($amount) && $amount <= 0)  return ["err_code" => $this->err_code = 45];

        $user = $this->db_user->exec_select_one("", "id=$id_user");
        $product = $this->db_product->exec_select_one("", "id=$id_product");
        $price = $product['price'];
        $store = $product['store'];
        $sold = $product['sold'];
        $money = $user['money'];
        $total_buyed = $store - $amount;
        $total_money = $amount * $price;
        $total_sold = $sold + $amount;
        $deduct_amount = $money - $total_money;

        if ($total_buyed < 0) return ["err_code" => $this->err_code = 46];
        if ($money < $total_money) return ["err_code" => $this->err_code = 47];

        $this->db_product->exec_update([
            "store" => $total_buyed,
            "sold" => $total_sold
        ], "id=$id_product");
        $this->db_user->exec_update([
            "money" => $deduct_amount
        ], "id=$id_user");
        $this->db_notification->exec_insert([
            "amount" => $amount,
            "user_id" => $id_user,
            "store_account_children_id" => $id_product,
            "money" => $total_money,
            "unique_code" => $random,
            "time" => time(),
            "is_show" => "T"
        ]);
        $this->db_account->exec_update([
            "is_sold" => "T",
            "user_id" => $id_user,
            "unique_code" => $random
        ], "store_account_children_id = $id_product AND is_sold='F' LIMIT $amount");
        $this->db_product->dis_connect();
        return ["err_code" => $this->err_code];
    }
}
