<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class Account extends Api
{
    private $db, $api;
    public function __construct()
    {
        $this->db = new DB();
        $this->api = new Api();
    }

    // Random
    public function GetAllAccountRandom($search = "", $limit_start = 0, $limit = 0)
    {
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND a.username LIKE '%$search%'" : "";

        $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, store_account_children s 
            WHERE (a.store_account_children_id = s.id) AND a.is_sold = 'F' AND a.type = 'random' $search $limit_start$limit";
        $accounts = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "accounts" => $accounts
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Danh sách tài khoản đang trống",
                "accounts" => []
            ]);
        }
    }
    public function AddAccountRandom($username, $password, $id_product)
    {
        $username = check_string($username);
        $password = check_string($password);
        $table = "account";
        $table_product = "store_account_children";
        $query_product = "SELECT * FROM $table_product WHERE id=$id_product";

        if (empty($username) || empty($password) || empty($id_product)) {
            return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu giá trị truyền vào"]);
        }
        if (!is_numeric($id_product)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã sản phẩm vui lòng đúng với id đã tạo"]);
        if ($this->db->num_rows($query_product) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Mã sản phẩm không khớp"]);

        try {
            $store = $this->db->get_row($query_product)['store'];

            $this->db->insert($table, [
                "username" => $username,
                "password" => $password,
                "store_account_children_id" => $id_product,
                "is_sold" => "F",
                "type" => "random",
            ]);
            $this->db->update($table_product, [
                'store' => $store + 1
            ], "id=$id_product");

            return json_encode_utf8(
                [
                    "errCode" => 0,
                    "status" => "success",
                    "message" => "Tạo account random thành công"
                ]
            );
        } catch (Exception) {
            return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Username đã được sử dụng"]);
        }
    }
    public function RemoveAccountRandom($id_account)
    {
        $table = "account";
        $query_account = "SELECT * FROM $table WHERE id=$id_account";

        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy account nào"]);

        $this->db->remove($table, "id=$id_account");
        return json_encode_utf8(
            [
                "errCode" => 0,
                "status" => "success",
                "message" => "Xoá dữ liệu thành công"
            ]
        );
    }
    public function EditAccountRandom($username, $password, $id_account, $id_product)
    {
        $username = check_string($username);
        $password = check_string($password);
        $table = "account";
        $table_product = "store_account_children";
        $query_product = "SELECT * FROM $table_product WHERE id=$id_product";
        $query_account = "SELECT * FROM $table WHERE id=$id_account";

        if (empty($username) || empty($password) || empty($id_product) || empty($id_account)) {
            return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu giá trị truyền vào"]);
        }
        if (!is_numeric($id_product)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã sản phẩm vui lòng đúng với id đã tạo"]);
        if ($this->db->num_rows($query_product) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Mã sản phẩm không khớp"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Id account vui lòng đúng với id đã tạo"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy tài khoản"]);

        try {
            $this->db->update($table, [
                "username" => $username,
                "password" => $password,
                "store_account_children_id" => $id_product,
            ], "id=$id_account");

            return json_encode_utf8(
                [
                    "errCode" => 0,
                    "status" => "success",
                    "message" => "Sửa account random thành công"
                ]
            );
        } catch (Exception) {
            return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Username đã được sử dụng"]);
        }
    }
    public function GetAccountRandomById($id_account)
    {
        $table = "account";
        $table_product = "store_account_children";
        $query_account = "SELECT a.id, a.username, a.password, s.title FROM $table a, $table_product s WHERE a.store_account_children_id = s.id AND a.is_sold = 'F' AND a.id=$id_account";

        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy account nào"]);

        $account = $this->db->get_row($query_account);
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "account" => $account
        ]);
    }
    // LOL
    public function GetAllAccountLOL($search = "", $limit_start = 0, $limit = 0)
    {
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND a.username LIKE '%$search%'" : "";

        $query = "SELECT a.id, a.username, a.password, l.id as name, a.is_sold, l.number_char, l.number_skin, i.name as rank, i.href, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol' $search $limit_start$limit";
        $accounts = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "accounts" => $accounts
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Danh sách tài khoản đang trống",
                "accounts" => []
            ]);
        }
    }
    public function RemoveAccountLOL($id_account)
    {
        $table = "account";
        $table_lol = "account_lol";
        $query_account = "SELECT * FROM $table WHERE id=$id_account";

        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy account nào"]);

        $this->db->remove($table_lol, "account_id=$id_account");
        $this->db->remove($table, "id=$id_account");
        return json_encode_utf8(
            [
                "errCode" => 0,
                "status" => "success",
                "message" => "Xoá dữ liệu thành công"
            ]
        );
    }
    public function AddAccountLOL($username, $password, $number_char, $number_skin, $id_rank, $price, $images)
    {
        $username = check_string($username);
        $password = check_string($password);
        $images = check_string($images);
        $table = "account";
        $table_lol = "account_lol";
        $table_rank = "images";
        $query_images = "SELECT * FROM $table_rank WHERE id=$id_rank AND type='rank_lol'";
        $query_account = "SELECT * FROM $table WHERE username='$username'";

        if (
            empty($username)
            || empty($password)
            || empty($number_char)
            || empty($number_skin)
            || empty($id_rank)
            || empty($price)
            || empty($images)
        ) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($number_char) || $number_char <= 0) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Số lượng tướng phải là số và phải lớn hơn 0"]);
        if (!is_numeric($number_skin) || $number_skin <= 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Số lượng trang phục phải là số và phải lớn hơn 0"]);
        if (!is_numeric($price) || $price <= 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Giá phải là số và phải lớn hơn 0"]);
        if (!is_numeric($id_rank)) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Rank phải là số"]);
        if ($this->db->num_rows($query_images) == 0) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Không tìm thấy rank"]);

        try {
            $this->db->insert($table, [
                "username" => $username,
                "password" => $password,
                "is_sold" => "F",
                "type" => "lol",
            ]);

            $id_account = $this->db->get_row($query_account)['id'];
            $this->db->insert($table_lol, [
                "number_char" => $number_char,
                "number_skin" => $number_skin,
                "rank_lol_id" => $id_rank,
                "price" => $price,
                "account_id" => $id_account,
                "image" => $images
            ]);

            return json_encode_utf8(
                [
                    "errCode" => 0,
                    "status" => "success",
                    "message" => "Tạo account lol thành công"
                ]
            );
        } catch (Exception) {
            return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Username đã được sử dụng"]);
        }
    }
    public function EditAccountLOL($id_account, $username, $password, $number_char, $number_skin, $id_rank, $price, $images)
    {
        $username = check_string($username);
        $password = check_string($password);
        $images = check_string($images);
        $table = "account";
        $table_lol = "account_lol";
        $table_rank = "images";
        $query_images = "SELECT * FROM $table_rank WHERE id=$id_rank AND type='rank_lol'";
        $query_account = "SELECT * FROM $table WHERE id=$id_account";

        if (
            empty($username)
            || empty($password)
            || empty($number_char)
            || empty($number_skin)
            || empty($id_rank)
            || empty($price)
        ) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($number_char) || $number_char <= 0) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Số lượng tướng phải là số và phải lớn hơn 0"]);
        if (!is_numeric($number_skin) || $number_skin <= 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Số lượng trang phục phải là số và phải lớn hơn 0"]);
        if (!is_numeric($price) || $price <= 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Giá phải là số và phải lớn hơn 0"]);
        if (!is_numeric($id_rank)) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Rank phải là số"]);
        if ($this->db->num_rows($query_images) == 0) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Không tìm thấy rank"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Id account phải là số"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Không tìm thấy account"]);

        try {
            $this->db->update($table, [
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

            return json_encode_utf8(
                [
                    "errCode" => 0,
                    "status" => "success",
                    "message" => "Sửa account lol thành công"
                ]
            );
        } catch (Exception) {
            return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Username đã được sử dụng"]);
        }
    }
    public function GetAccountLOLByIdAccount($id_account)
    {
        $table = "account";
        $table_lol = "account_lol";
        $table_rank = "images";
        $query_account = "SELECT a.id, a.username, a.password, l.id as name, l.rank_lol_id, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM $table a, $table_lol l, $table_rank i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol' AND a.id = $id_account";

        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy account nào"]);

        $account = $this->db->get_row($query_account);
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "account" => $account
        ]);
    }
    public function BuyAccountLOL($id_account, $id_user)
    {
        $random = random_string();
        $table = "account";
        $table_user = "user";
        $table_lol = "account_lol";
        $table_notification = "notification_buy";
        $query_user = "SELECT * FROM $table_user WHERE id=$id_user";
        $query_account = "SELECT * FROM $table WHERE id=$id_account AND is_sold='F' AND type='lol'";
        $query_lol = "SELECT * FROM $table_lol WHERE account_id=$id_account";

        if (empty($id_account) || empty($id_user)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account) || !is_numeric($id_user)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "id vui lòng là số"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy user"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy account"]);
        if ($this->db->num_rows($query_lol) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy account lol"]);

        $user = $this->db->get_row($query_user);
        $lol = $this->db->get_row($query_lol);
        $id_lol = $lol['id'];
        $money = $user['money'];
        $price = $lol['price'];
        if ($price > $money) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Số tiền không đủ"]);

        $this->db->update($table, [
            "user_id" => $id_user,
            "unique_code" => $random,
            "is_sold" => "T",
        ], "id=$id_account AND is_sold='F'");
        $this->db->insert($table_notification, [
            "money" => $price,
            "amount" => 1,
            "user_id" => $id_user,
            "unique_code" => $random,
            "account_lol_id" => $id_lol,
            "time" => time(),
            "is_show" => 'T'
        ]);
        $this->db->update($table_user, [
            "money" => $money - $price,
        ], "id=$id_user");
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Mua thành công",
        ]);
    }
}
