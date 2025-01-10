<?php
require_once __DIR__ . "/../config.php";

class User
{
    private $db_user, $db_bank, $db_notification, $err_code = 0;
    public function __construct()
    {
        $this->db_user = new UserDB();
        $this->db_bank = new BankDB();
        $this->db_notification = new NotificationDB();
    }
    public function Login($username, $password)
    {
        $username = check_string($username);
        $password = hash_encode(check_string($password));

        if (empty($username) || empty($password)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_user->check_login($username, $password))  return ["err_code" => $this->err_code = 2];

        $data = $this->db_user->exec_select_one("id, username, name, role_id, age, email, avatar, number_phone, time_login, time_created", "username = '$username' AND password = '$password'");

        $this->db_user->dis_connect();
        return [
            "err_code" => $this->err_code,
            "data" => $data
        ];
    }
    public function Register($username, $password, $password_verify, $name, $email)
    {
        $username = check_string($username);
        $password = check_string($password);
        $password_verify = check_string($password_verify);
        $hash_password = hash_encode($password);
        $name = check_string($name);
        $email = check_string($email);

        if (empty($username) || empty($password) || empty($name) || empty($email)) return ["err_code" => $this->err_code = 1];
        if (strlen($username) < 5 || strlen($username) > 20) return ["err_code" => $this->err_code = 3];
        if (strlen($password) < 8 || strlen($password) > 16) return ["err_code" => $this->err_code = 4];
        if (strlen($name) > 40) return ["err_code" => $this->err_code = 5];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ["err_code" => $this->err_code = 6];
        if ($password != $password_verify) return ["err_code" => $this->err_code = 7];
        if ($this->db_user->check_username_exist($username)) return ["err_code" => $this->err_code = 8];

        $this->db_user->exec_insert([
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

        $this->db_user->dis_connect();
        return [
            "err_code" => $this->err_code
        ];
    }
    public function GetUserById($id_user)
    {
        $where = "id=$id_user";
        if (empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];

        $total_money = $this->db_bank->total_money_user($id_user);
        $spent = $this->db_notification->money_spent_user($id_user);

        $data = $this->db_user->exec_select_one(null, $where);
        $data['total_money'] = $total_money;
        $data['spent'] = $spent;

        $this->db_user->dis_connect();
        return [
            "err_code" => $this->err_code,
            "data" => $data
        ];
    }
    public function RemoveUser($id_user)
    {
        $where = "id=$id_user";
        if (empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];


        $this->db_user->exec_remove($where);

        $this->db_user->dis_connect();
        return [
            "err_code" => $this->err_code
        ];
    }
    public function GetAllUser($search, $limit_start, $limit)
    {
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];

        $data = $this->db_user->exec_search("id, username, email, number_phone, money, role_id", $search, $limit_start, $limit);

        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $id = $data[$i]['id'];
                $total_money = $this->db_bank->total_money_user($id);

                $data[$i]['total_money'] = $total_money;
            }
        }

        $this->db_user->dis_connect();
        return [
            "err_code" => $this->err_code,
            "data" => isset($data) ? $data : []
        ];
    }
    public function EditUser($id_user, $username, $password, $name, $age, $email, $number_phone, $avatar, $money, $role_id)
    {
        $where = "id=$id_user";
        $username = check_string($username);
        $password = check_string($password);
        $name = check_string($name);
        $age = check_string($age);
        $email = check_string($email);

        if (empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if (!empty($age) && (strtotime($age) <= strtotime(0)))  return ["err_code" => $this->err_code = 17];
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) return ["err_code" => $this->err_code = 18];
        if (!empty($number_phone) && !is_numeric($number_phone)) return ["err_code" => $this->err_code = 19];
        if (!empty($money) && !is_numeric($money) && $money > 0)  return ["err_code" => $this->err_code = 20];
        if (!empty($role_id) && !is_numeric($role_id) && ($role_id != 0 || $role_id != 2))  return ["err_code" => $this->err_code = 21];

        $user = $this->db_user->exec_select_one("", $where);

        $username = !empty($username) ? $username : $user['username'];
        $password = !empty($password) ? hash_encode($password) : $user['password'];
        $name = !empty($name) ? $name : $user['name'];
        $email = !empty($email) ? $email : $user['email'];
        $number_phone = !empty($number_phone) ? $number_phone : $user['number_phone'];
        $money = !empty($money) ? $money : $user['money'];
        $role_id = !empty($role_id) ? $role_id : $user['role_id'];
        $age = !empty($age) ? $age : $user['age'];

        try {
            $this->db_user->exec_update([
                "username" => $username,
                "password" => $password,
                "name" => $name,
                "email" => $email,
                "number_phone" => $number_phone,
                "money" => $money,
                "role_id" => $role_id,
                "age" => $age,
            ], $where);
        } catch (Exception) {
            $this->db_user->dis_connect();
            return ["err_code" => $this->err_code = 8];
        }
        $this->db_user->dis_connect();
        return [
            "err_code" => $this->err_code
        ];
    }
}
