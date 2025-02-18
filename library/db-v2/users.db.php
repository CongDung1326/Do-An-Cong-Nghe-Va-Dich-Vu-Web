<?php
require_once __DIR__ . "/../../config.php";
class UsersDB extends DB
{
    private $table = "users";
    public function exec_select_list($select, $where)
    {
        $where = trim($where);

        $select = !empty($select) ? $select : "*";
        $query = !empty($where) ? "SELECT $select FROM {$this->table} WHERE $where" : "SELECT $select FROM {$this->table}";
        $result = $this->get_list($query);

        return $result;
    }
    public function exec_select_row($select, $where)
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
    public function exec_login($username, $password)
    {
        $username = check_string($username);
        $passwordHash = hash_encode(check_string($password));

        if ($this->exec_num_rows("username='$username' AND passwordHash='$passwordHash'") == 0) return 1;

        return 0;
    }
    public function exec_find_login_by_username_password($username, $password)
    {
        $username = check_string($username);
        $passwordHash = hash_encode(check_string($password));

        return $this->exec_select_row("*", "username='$username' AND passwordHash='$passwordHash'");
    }
}
