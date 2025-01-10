<?php
require_once __DIR__ . "/../config.php";

class Account
{
    private $db, $db_account, $db_user, $db_notification, $db_product, $db_images, $err_code = 0;
    public function __construct()
    {
        $this->db = new DB();
        $this->db_account = new AccountDB();
        $this->db_user = new UserDB();
        $this->db_notification = new NotificationDB();
        $this->db_product = new ProductDB();
        $this->db_images = new ImagesDB();
    }

    // Random
    public function GetAllAccountRandom($search, $limit_start, $limit, $id_user, $is_sold, $id_notification)
    {
        if ((!empty($id_notification) && !is_numeric($id_notification))
            || (!empty($id_user) && !is_numeric($id_user))
        ) return ["err_code" => $this->err_code = 9];
        if (!empty($id_user) && !$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if (!empty($id_notification) && !$this->db_notification->check_notification_exist($id_notification)) return ["err_code" => $this->err_code = 28];
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];
        if ($is_sold != "ALL" && $is_sold != "T" && $is_sold != "F") return ["err_code" => $this->err_code = 29];

        $unique_code = "";
        if (!empty($id_notification)) {
            $unique_code = $this->db_notification->get_unique_code($id_notification);
            if (!$this->db_account->check_account_has_unique_code($unique_code)) return ["err_code" => $this->err_code = 30];
        }
        $accounts = $this->db_account->exec_search_random($search, $limit_start, $limit, $id_user, $is_sold, $unique_code);
        if (count($accounts) > 0) {
            return ["err_code" => $this->err_code, "data" => $accounts];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function AddAccountRandom($username, $password, $id_product)
    {
        $username = check_string($username);
        $password = check_string($password);

        if (empty($username) || empty($password) || empty($id_product)) {
            return ["err_code" => $this->err_code = 1];
        }
        if (!is_numeric($id_product)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_product->check_product_exist($id_product)) return ["err_code" => $this->err_code = 31];

        try {
            $store = $this->db_product->exec_select_one("store", "id=$id_product")['store'];

            $this->db_account->exec_insert([
                "username" => $username,
                "password" => $password,
                "store_account_children_id" => $id_product,
                "is_sold" => "F",
                "type" => "random",
            ]);
            $this->db_product->exec_update([
                'store' => $store + 1
            ], "id=$id_product");

            return ["err_code" => $this->err_code];
        } catch (Exception) {
            return ["err_code" => $this->err_code = 8];
        }
    }
    public function RemoveAccountRandom($id_account)
    {
        if (empty($id_account)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_account->check_account_exist($id_account)) return ["err_code" => $this->err_code = 32];

        $id_product = $this->db_account->exec_select_one("store_account_children_id", "id=$id_account")['store_account_children_id'];
        $store = $this->db_product->exec_select_one("store", "id=$id_product")['store'];

        $this->db_account->exec_remove("id=$id_account");
        $this->db_product->exec_update([
            "store" => $store - 1
        ], "id=$id_product");
        return ["err_code" => $this->err_code];
    }
    public function EditAccountRandom($username, $password, $id_account, $id_product)
    {
        $username = check_string($username);
        $password = check_string($password);

        if (empty($username) || empty($password) || empty($id_product) || empty($id_account)) {
            return ["err_code" => $this->err_code = 1];
        }
        if (!is_numeric($id_product) || !is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_product->check_product_exist($id_product))  return ["err_code" => $this->err_code = 31];
        if (!$this->db_account->check_account_exist($id_account))  return ["err_code" => $this->err_code = 32];

        if (!$this->db_account->exec_update_account_random($username, $password, $id_account, $id_product))
            return ["err_code" => $this->err_code = 8];

        return ["err_code" => $this->err_code];
    }
    public function GetAccountRandomById($id_account, $is_sold = "F")
    {
        if (empty($id_account)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if ($is_sold != "F" && $is_sold != "T") return ["err_code" => $this->err_code = 29];

        if (!$this->db_account->check_account_is_sold($id_account, $is_sold, "")) return ["err_code" => $this->err_code = 32];

        $data = $this->db_account->exec_get_account_random_have_sold($id_account, $is_sold);
        return ["err_code" => $this->err_code, "data" => $data];
    }
    // LOL
    public function GetAllAccountLOL($search, $limit_start, $limit, $id_user, $is_sold, $id_notification)
    {
        if ((!empty($id_notification) && !is_numeric($id_notification))
            || (!empty($id_user) && !is_numeric($id_user))
        ) return ["err_code" => $this->err_code = 9];
        if (!empty($id_user) && !$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if (!empty($id_notification) && !$this->db_notification->check_notification_exist($id_notification)) return ["err_code" => $this->err_code = 28];
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];
        if ($is_sold != "ALL" && $is_sold != "T" && $is_sold != "F") return ["err_code" => $this->err_code = 29];

        $unique_code = "";
        if (!empty($id_notification)) {
            $unique_code = $this->db_notification->get_unique_code($id_notification);
            if (!$this->db_account->check_account_has_unique_code($unique_code)) return ["err_code" => $this->err_code = 30];
        }

        $accounts = $this->db_account->exec_search_lol($search, $limit_start, $limit, $id_user, $is_sold, $unique_code);
        if (count($accounts) > 0) {
            return ["err_code" => $this->err_code, "data" => $accounts];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function RemoveAccountLOL($id_account)
    {
        $table_lol = "account_lol";

        if (empty($id_account)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_account->check_account_exist($id_account)) return ["err_code" => $this->err_code = 32];

        $this->db->remove($table_lol, "account_id=$id_account");
        $this->db_account->exec_remove("id=$id_account");
        return ["err_code" => $this->err_code];
    }
    public function AddAccountLOL($username, $password, $number_char, $number_skin, $id_rank, $price, $images)
    {
        $username = check_string($username);
        $password = check_string($password);
        $images = check_string($images);
        $table_lol = "account_lol";

        if (
            empty($username)
            || empty($password)
            || empty($number_char)
            || empty($number_skin)
            || empty($id_rank)
            || empty($price)
            || empty($images)
        ) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($number_char) || $number_char <= 0) return ["err_code" => $this->err_code = 33];
        if (!is_numeric($number_skin) || $number_skin <= 0) return ["err_code" => $this->err_code = 34];
        if (!is_numeric($price) || $price <= 0) return ["err_code" => $this->err_code = 20];
        if (!is_numeric($id_rank)) return ["err_code" => $this->err_code = 9];
        if ($this->db_images->check_image_exist($id_rank) == 0) return ["err_code" => $this->err_code = 35];

        try {
            $this->db_account->exec_insert([
                "username" => $username,
                "password" => $password,
                "is_sold" => "F",
                "type" => "lol",
            ]);

            $id_account = $this->db_account->exec_select_one("id", "username='$username'")['id'];
            $this->db->insert($table_lol, [
                "number_char" => $number_char,
                "number_skin" => $number_skin,
                "rank_lol_id" => $id_rank,
                "price" => $price,
                "account_id" => $id_account,
                "image" => $images
            ]);

            return ["err_code" => $this->err_code];
        } catch (Exception) {
            return ["err_code" => $this->err_code = 8];
        }
    }
    public function EditAccountLOL($id_account, $username, $password, $number_char, $number_skin, $id_rank, $price, $images)
    {
        $username = check_string($username);
        $password = check_string($password);
        $images = check_string($images);
        $table_lol = "account_lol";

        if (
            empty($username)
            || empty($password)
            || empty($number_char)
            || empty($number_skin)
            || empty($id_rank)
            || empty($price)
        ) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($number_char) || $number_char <= 0) return ["err_code" => $this->err_code = 33];
        if (!is_numeric($number_skin) || $number_skin <= 0) return ["err_code" => $this->err_code = 34];
        if (!is_numeric($price) || $price <= 0) return ["err_code" => $this->err_code = 20];
        if (!is_numeric($id_rank) || !is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if ($this->db_images->check_image_exist($id_rank) == 0) return ["err_code" => $this->err_code = 35];
        if (!$this->db_account->check_account_exist($id_account)) return ["err_code" => $this->err_code = 32];

        try {
            $this->db_account->exec_update([
                "username" => $username,
                "password" => $password,
            ], "id=$id_account");
            $this->db->update($table_lol, [
                "number_char" => $number_char,
                "number_skin" => $number_skin,
                "rank_lol_id" => $id_rank,
                "price" => $price,
            ], "account_id=$id_account");
            if (!empty($images)) {
                $this->db->update($table_lol, [
                    "image" => $images,
                ], "account_id=$id_account");
            }

            return ["err_code" => $this->err_code];
        } catch (Exception) {
            return ["err_code" => $this->err_code = 8];
        }
    }
    public function GetAccountLOLByIdAccount($id_account, $is_sold = "F")
    {
        if (empty($id_account)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if ($is_sold != "F" && $is_sold != "T") return ["err_code" => $this->err_code = 29];
        if (!$this->db_account->check_account_is_sold($id_account, $is_sold, "lol")) return ["err_code" => $this->err_code = 32];

        $account = $this->db_account->exec_get_account_lol_have_sold($id_account, $is_sold);
        return ["err_code" => $this->err_code, "data" => $account];
    }
    public function BuyAccountLOL($id_account, $id_user)
    {
        $random = random_string();
        $table_lol = "account_lol";
        $query_lol = "SELECT * FROM $table_lol WHERE account_id=$id_account";

        if (empty($id_account) || empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account) || !is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if (!$this->db_account->check_account_exist($id_account)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy account"]);
        if ($this->db->num_rows($query_lol) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy account lol"]);

        $user = $this->db_user->exec_select_one("", "id=$id_user");
        $lol = $this->db->get_row($query_lol);
        $id_lol = $lol['id'];
        $money = $user['money'];
        $price = $lol['price'];
        if ($price > $money) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Số tiền không đủ"]);

        $this->db_account->exec_update([
            "user_id" => $id_user,
            "unique_code" => $random,
            "is_sold" => "T",
        ], "id=$id_account AND is_sold='F'");
        $this->db_notification->exec_insert([
            "money" => $price,
            "amount" => 1,
            "user_id" => $id_user,
            "unique_code" => $random,
            "account_lol_id" => $id_lol,
            "time" => time(),
            "is_show" => 'T'
        ]);
        $this->db_user->exec_update([
            "money" => $money - $price,
        ], "id=$id_user");
        return ["err_code" => $this->err_code];
    }
    public function GetAllAccountBuyed($search, $limit_start, $limit, $id_account)
    {
        if (!empty($id_account) && !is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];

        $accounts = $this->db_account->exec_search_account_buyed($search, $limit_start, $limit, $id_account);
        if (count($accounts) > 0) {
            if (!empty($id_account)) {
                $type = $accounts[0]['type'];
                $id = $accounts[0]['id'];

                switch ($type) {
                    case "lol":
                        $account_lol = $this->GetAccountLOLByIdAccount($id, "T");
                        return $account_lol;
                    case "random":
                        $account_random = $this->GetAccountRandomById($id, "T");
                        return $account_random;
                    default:
                        return ["err_code" => $this->err_code = 36];
                }
            }
            return ["err_code" => $this->err_code, "data" => $accounts];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function RemoveAccountBuyed($id_account)
    {
        $table_lol = "account_lol";

        if (empty($id_account)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_account->check_account_exist($id_account)) return ["err_code" => $this->err_code = 32];

        $account = $this->db_account->exec_select_one("", "id=$id_account");
        $type = $account['type'];

        switch ($type) {
            case "lol":
                $id_lol = $this->db->get_row("SELECT id FROM $table_lol WHERE account_id=$id_account")['id'];

                $this->db_notification->exec_remove("account_lol_id=$id_lol");
                $this->db->remove($table_lol, "account_id=$id_account");
                $this->db_account->exec_remove("id=$id_account");
                break;
            case "random":
                $this->db_notification->exec_remove("unique_code='{$account['unique_code']}'");
                $this->db_account->exec_remove("id=$id_account");
                break;

            default:
                return ["err_code" => $this->err_code = 36];
        }

        return ["err_code" => $this->err_code];
    }
}
