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
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Lấy dữ liệu thành công",
            "user" => $user
        ]);
    }
}
