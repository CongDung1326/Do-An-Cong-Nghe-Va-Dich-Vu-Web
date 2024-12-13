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
    public function GetAllAccountRandom($search = "", $limit_start = 0, $limit = 0, $id_user, $is_sold = "ALL", $id_notification, $data)
    {
        $table_user = "user";
        $query_user =  "SELECT * FROM $table_user WHERE id = $id_user";
        $query_notification = "SELECT * FROM notification_buy WHERE id = $id_notification";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if ((!empty($id_notification) && !is_numeric($id_notification))
            || (!empty($id_user) && !is_numeric($id_user))
        ) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "id vui lòng phải là số"]);
        if (!empty($id_user) && $this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy user"]);
        if (!empty($id_notification) && $this->db->num_rows($query_notification) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy đơn nào hàng nào cả"]);
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 10, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if ($is_sold != "ALL" && $is_sold != "T" && $is_sold != "F") return json_encode_utf8(["errCode" => 11, "status" => "error", "message" => "Đã bán chỉ nhận T hoặc F"]);

        if (!empty($id_notification)) {
            $unique_code = $this->db->get_row($query_notification)['unique_code'];
            $query_account_has_unique = "SELECT * FROM account WHERE unique_code = '$unique_code'";
            if ($this->db->num_rows($query_account_has_unique) == 0) return json_encode_utf8(["errCode" => 11, "status" => "error", "message" => "Tài khoản đã bị xoá hoặc bị lỗi"]);
        }

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND a.username LIKE '%$search%'" : "";
        $id_user = !empty($id_user) ? "AND a.user_id = $id_user" : "";
        $is_sold = $is_sold != "ALL" ? "AND a.is_sold = '$is_sold'" : "";
        $unique_code = !empty($unique_code) ? "AND a.unique_code = '$unique_code'" : "";

        $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, store_account_children s 
            WHERE (a.store_account_children_id = s.id) $is_sold AND a.type = 'random' $id_user $unique_code $search $limit_start$limit";
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
        $table_product = "store_account_children";
        $query_account = "SELECT * FROM $table WHERE id=$id_account";

        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy account nào"]);

        $account = $this->db->get_row($query_account);
        $id_product = $account['store_account_children_id'];
        $product = $this->db->get_row("SELECT * FROM $table_product WHERE id=$id_product");
        $store = $product['store'];

        $this->db->remove($table, "id=$id_account");
        $this->db->update($table_product, [
            "store" => $store - 1
        ], "id=$id_product");
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
    public function GetAccountRandomById($id_account, $is_sold = "F")
    {
        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($is_sold != "F" && $is_sold != "T") return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Trạng thái bán vui lòng phải là T|F"]);

        $table = "account";
        $table_product = "store_account_children";
        $is_sold = "AND a.is_sold = '$is_sold'";
        $query_account = "SELECT a.id, a.username, a.password, a.type, s.title FROM $table a, $table_product s WHERE a.store_account_children_id = s.id $is_sold AND a.id=$id_account";


        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy account nào"]);

        $account = $this->db->get_row($query_account);
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "account" => $account
        ]);
    }
    // LOL
    public function GetAllAccountLOL($search = "", $limit_start = 0, $limit = 0, $id_user, $is_sold = "ALL", $id_notification, $data)
    {
        $table_user = "user";
        $query_user =  "SELECT * FROM $table_user WHERE id = $id_user";
        $query_notification = "SELECT * FROM notification_buy WHERE id = $id_notification";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if ((!empty($id_notification) && !is_numeric($id_notification))
            || (!empty($id_user) && !is_numeric($id_user))
        ) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "id vui lòng phải là số"]);
        if (!empty($id_user) && $this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy user"]);
        if (!empty($id_notification) && $this->db->num_rows($query_notification) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy đơn nào hàng nào cả"]);
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 10, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if ($is_sold != "ALL" && $is_sold != "T" && $is_sold != "F") return json_encode_utf8(["errCode" => 11, "status" => "error", "message" => "Đã bán chỉ nhận T hoặc F"]);

        if (!empty($id_notification)) {
            $unique_code = $this->db->get_row($query_notification)['unique_code'];
            $query_account_has_unique = "SELECT * FROM account WHERE unique_code = '$unique_code'";
            if ($this->db->num_rows($query_account_has_unique) == 0) return json_encode_utf8(["errCode" => 11, "status" => "error", "message" => "Tài khoản đã bị xoá hoặc bị lỗi"]);
        }

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND a.username LIKE '%$search%'" : "";
        $id_user = !empty($id_user) ? "AND a.user_id = $id_user" : "";
        $is_sold = $is_sold != "ALL" ? "AND a.is_sold = '$is_sold'" : "";
        $unique_code = !empty($unique_code) ? "AND a.unique_code = '$unique_code'" : "";

        $query = "SELECT a.id, a.username, a.password, l.id as name, a.is_sold, l.number_char, l.number_skin, i.name as rank, i.href, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) $is_sold $id_user $unique_code AND a.type = 'lol' $search $limit_start$limit";
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
    public function GetAccountLOLByIdAccount($id_account, $is_sold = "F")
    {
        if (empty($id_account)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Mã id vui lòng là số"]);
        if ($is_sold != "F" && $is_sold != "T") return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Trạng thái bán vui lòng phải là T|F"]);

        $table = "account";
        $table_lol = "account_lol";
        $table_rank = "images";
        $is_sold = "AND a.is_sold = '$is_sold'";
        $query_account = "SELECT a.id, a.username, a.password, l.id as name,a.type , l.rank_lol_id, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM $table a, $table_lol l, $table_rank i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) $is_sold AND a.type = 'lol' AND a.id = $id_account";

        if ($this->db->num_rows($query_account) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy account nào"]);

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
    public function GetAllAccountBuyed($search = "", $limit_start = 0, $limit = 0, $id_account, $data)
    {
        $table = "account";
        $table_user = "user";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (!empty($id_account) && !is_numeric($id_account)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "id vui lòng phải là số"]);
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND (a.username LIKE '%$search%' OR a.unique_code LIKE '%$search%')" : "";
        $id_account = !empty($id_account) ? "AND a.id = $id_account" : "";
        $query = "SELECT a.id, a.username, a.password, a.is_sold, u.username as user_username, a.unique_code , a.type
        FROM $table a, $table_user u 
        WHERE a.user_id = u.id AND a.is_sold = 'T' $id_account $search $limit_start$limit";
        $accounts = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
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
                        return json_encode_utf8([
                            "errCode" => 9,
                            "status" => "error",
                            "message" => "Không tìm thấy kiểu của account",
                        ]);
                }
            }
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
    public function RemoveAccountBuyed($id_account, $data)
    {
        $table = "account";
        $table_lol = "account_lol";
        $table_notification = "notification_buy";

        $query = "SELECT * FROM $table WHERE id=$id_account";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_account)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_account)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Mã tài khoản vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy account"]);

        $account = $this->db->get_row($query);
        $type = $account['type'];

        switch ($type) {
            case "lol":
                $id_lol = $this->db->get_row("SELECT id FROM $table_lol WHERE account_id=$id_account")['id'];

                $this->db->remove($table_notification, "account_lol_id=$id_lol");
                $this->db->remove($table_lol, "account_id=$id_account");
                $this->db->remove($table, "id=$id_account");
                break;
            case "random":
                $this->db->remove($table, "id=$id_account");
                break;

            default:
                return json_encode_utf8([
                    "errCode" => 9,
                    "status" => "error",
                    "message" => "Không tìm thấy kiểu của account",
                ]);
        }

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xoá thành công",
        ]);
    }
}
