<?php
require_once __DIR__ . "/../../config.php";

class UserDB extends DB
{
    private $table = "user", $err_code = 0, $db_notification, $db_bank;
    public function __construct()
    {
        $this->db_notification = new NotificationDB();
        $this->db_bank = new BankDB();
    }
    public function exec_select_all($select, $where)
    {
        $where = trim($where);

        $select = !empty($select) ? $select : "*";
        $query = !empty($where) ? "SELECT $select FROM {$this->table} WHERE $where" : "SELECT $select FROM {$this->table}";
        $result = $this->get_list($query);

        return $result;
    }
    public function exec_select_one($select, $where)
    {
        $where = trim($where);

        $select = !empty($select) ? $select : "*";
        $query = !empty($where) ? "SELECT $select FROM {$this->table} WHERE $where" : "SELECT $select FROM {$this->table}";
        $result = $this->get_row($query);

        return $result;
    }
    public function exec_num_rows($where)
    {
        $query = !is_null($where) ? "SELECT username FROM {$this->table} WHERE $where" : "SELECT * FROM {$this->table}";
        $result = $this->num_rows($query);

        return $result;
    }
    public function exec_insert($data)
    {
        return $this->insert($this->table, $data);
    }
    public function exec_update($data, $where)
    {
        return $this->update($this->table, $data, $where);
    }
    public function exec_remove($where)
    {
        return $this->remove($this->table, $where);
    }
    public function check_login($username, $password)
    {
        return $this->exec_num_rows("username='$username' AND password='$password'") > 0 ? true : false;
    }
    public function check_username_exist($username)
    {
        return  $this->exec_num_rows("username='$username'") > 0 ? true : false;
    }
    public function exec_search($select, $search, $limit_start, $limit)
    {
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = "username LIKE '%$search%'";

        return $this->exec_select_all($select, "$search $limit_start$limit");
    }
    public function check_user_exist($id_user)
    {
        return $this->exec_num_rows("id=$id_user") > 0 ? true : false;
    }
    public function check_admin($id_user)
    {
        return $this->exec_num_rows("id=$id_user AND role_id=2") > 0 ? true : false;
    }
    public function sum_user()
    {
        return $this->exec_select_one("COUNT(*) as users", null)['users'];
    }
}
