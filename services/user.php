<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class User extends Api
{
    private $db, $api;
    public function __construct()
    {
        $this->db = new DB();
        $this->api = new Api();
    }
    public function Login($username, $password)
    {
        $username = check_string($username);
        $password = hash_encode(check_string($password));
        $table = "user";
        $query_user = "SELECT id, username, name, role_id, age, email, avatar, number_phone, time_login, time_created FROM $table WHERE username = '$username' AND password = '$password'";

        if (empty($username) || empty($password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Tài khoản hoặc mật khẩu sai"]);

        $user = $this->db->get_row($query_user);
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "user" => $user
        ]);
    }
    public function Register($username, $password, $password_verify, $name, $email)
    {
        $username = check_string($username);
        $password = check_string($password);
        $password_verify = check_string($password_verify);
        $hash_password = hash_encode($password);
        $name = check_string($name);
        $email = check_string($email);
        $table = "user";

        if (empty($username) || empty($password) || empty($name) || empty($email)) return  json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (strlen($username) < 5 || strlen($username) > 20) return  json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Tên tài khoản vui lòng dài từ 5 đến 20 ký tự"]);
        if (strlen($password) < 8 || strlen($password) > 16) return  json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Mật khẩu vui lòng dài từ 8 đến 16 ký tự"]);
        if (strlen($name) > 40) return  json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Tên quá dài"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng đúng định dạng email"]);
        if ($password != $password_verify) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Mật khẩu không trùng khớp"]);

        try {
            $this->db->insert($table, [
                "username" => $username,
                "password" => $hash_password,
                "name" => $name,
                "email" => $email,
                "role_id" => 0,
                "time_login" => time(),
                "time_created" => time(),
                "avatar" => "assets/storage/default_avatar.jpg",
                "money" => 0
            ]);
        } catch (Exception) {
            return  json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Tài khoản đã được sử dụng"]);
        }

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Đăng ký thành công",
        ]);
    }
    public function GetUserById($id_user, $data)
    {
        $table = "user";
        $query = "SELECT * FROM $table WHERE id=$id_user";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_user)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "id vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy user nào cả"]);

        $user = $this->db->get_row($query);
        $total_money = $this->db->get_row("SELECT SUM(amount) as result FROM bank WHERE user_id = $id_user AND status = 'S'")['result'];
        $spent = $this->db->get_row("SELECT SUM(money) as result FROM notification_buy WHERE user_id = $id_user")['result'];

        $user['total_money'] = $total_money;
        $user['spent'] = $spent;

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "user" => $user
        ]);
    }
    public function GetDataBanner($data)
    {
        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);

        $buyed = $this->db->get_row("SELECT COUNT(*) as buyed FROM notification_buy")['buyed'];
        $account_sold = $this->db->get_row("SELECT COUNT(*) as sold FROM account WHERE is_sold = 'T'")['sold'];
        $count_user = $this->db->get_row("SELECT COUNT(*) as users FROM user")['users'];
        $sold = $this->db->get_row("SELECT SUM(money) as money FROM notification_buy")['money'];

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "data" => [
                "buyed" => $buyed,
                "account_sold" => $account_sold,
                "count_user" => $count_user,
                "sold" => $sold
            ]
        ]);
    }
    public function RemoveUser($id_user, $data)
    {
        $table = "user";
        $query = "SELECT * FROM $table WHERE id=$id_user";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy user"]);

        $this->db->remove($table, "id=$id_user");

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xoá dữ liệu thành công",
        ]);
    }
    public function GetAllUser($search, $limit_start, $limit, $data)
    {
        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);


        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "WHERE u.username LIKE '%$search%'" : "";

        $query = "SELECT u.id, u.username, u.email, u.number_phone, u.money, u.role_id FROM user u $search $limit_start$limit";
        $users = $this->db->get_list($query);

        if ($this->db->num_rows($query) > 0) {
            for ($i = 0; $i < count($users); $i++) {
                $id = $users[$i]['id'];
                $total_money = $this->db->get_row("SELECT SUM(amount) as money_sum FROM bank WHERE user_id = $id AND status='S'")['money_sum'];

                $users[$i]['total_money'] = $total_money;
            }

            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "users" => $users
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Không tìm thấy người dùng nào",
                "users" => []
            ]);
        }
    }
    public function EditUser($id_user, $username, $password, $name, $age, $email, $number_phone, $avatar, $money, $role_id, $data)
    {
        $table = "user";
        $query = "SELECT * FROM $table WHERE id=$id_user";
        $username = check_string($username);
        $password = check_string($password);
        $name = check_string($name);
        $age = check_string($age);
        $email = check_string($email);

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_user)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Id vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy user"]);
        if (!empty($age) && !is_numeric($age))  return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Tuổi vui lòng phải là số"]);
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Vui lòng đúng định dạng email"]);
        if (!empty($number_phone) && !is_numeric($number_phone))  return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Số điện thoại vui lòng phải là số"]);
        if (!empty($money) && !is_numeric($money) && $money > 0)  return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Số tiền vui lòng phải là số và nó phải lớn hơn 0"]);
        if (!empty($role_id) && !is_numeric($role_id) && ($role_id != 0 || $role_id != 2))  return json_encode_utf8(["errCode" => 10, "status" => "error", "message" => "Quyền hạng vui lòng phải là số và phải nằm trong 0 và 2"]);

        $user = $this->db->get_row($query);

        $username = !empty($username) ? $username : $user['username'];
        $password = !empty($password) ? hash_encode($password) : $user['password'];
        $name = !empty($name) ? $name : $user['name'];
        $email = !empty($email) ? $email : $user['email'];
        $number_phone = !empty($number_phone) ? $number_phone : $user['number_phone'];
        $money = !empty($money) ? $money : $user['money'];
        $role_id = !is_null($role_id) ? $role_id : $user['role_id'];
        $age = !empty($age) ? $age : $user['age'];

        try {
            $this->db->update($table, [
                "username" => $username,
                "password" => $password,
                "name" => $name,
                "email" => $email,
                "number_phone" => $number_phone,
                "money" => $money,
                "role_id" => $role_id,
                "age" => $age,
            ], "id=$id_user");
        } catch (Exception) {
            return json_encode_utf8([
                "errCode" => 11,
                "status" => "error",
                "message" => "Trùng tên tài khoản",
            ]);
        }

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Cập nhật thành công",
        ]);
    }
    public function EditSettings($id_user, $title, $description, $logo, $keyword, $name_shop, $discount, $data)
    {
        $table = "settings";
        $query_user = "SELECT * FROM user WHERE id=$id_user AND role_id = 2";
        $title = check_string($title);
        $description = check_string($description);
        $keyword = check_string($keyword);
        $name_shop = check_string($name_shop);

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_user) && empty($title) && empty($description) && empty($keyword) && empty($name_shop) && empty($discount)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!empty($discount) && !is_numeric($discount)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Chiết khấu vui lòng phải là số"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy tài khoản"]);

        $result = [
            "title" => $title,
            "description" => $description,
            "keyword" => $keyword,
            "name_shop" => $name_shop,
            "discount" => $discount,
        ];
        if (!empty($logo)) $result['logo'] = $logo;
        foreach ($result as $key => $value) {
            $this->db->update($table, [
                "value" => $value
            ], "name = '$key'");
        }

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Cập nhật thành công",
        ]);
    }
}
