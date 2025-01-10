<?php
require_once __DIR__ . "/../../config.php";

class BankDB extends DB
{
    private $table = "bank";
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
        $query = !is_null($where) ? "SELECT id FROM {$this->table} WHERE $where" : "SELECT * FROM {$this->table}";
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
    public function total_money_user($id_user)
    {
        return $this->exec_select_one("SUM(amount) as result", "user_id = $id_user AND status = 'S'")['result'];
    }
    public function exec_search_bank($limit_start, $limit, $search, $status)
    {
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $order_by = $status == "ALL" ? "ORDER BY FIELD(b.status, 'W','S','F')" : "ORDER BY time_created DESC";
        $search = !empty($search) ? "AND u.username LIKE '%$search%'" : "";
        $status = $status == "ALL" ? "" : "AND b.status = '$status'";
        $table = "bank";
        $table_user = "user";
        $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.status, b.time_created, u.name , u.username, u.id as id_user
        FROM $table b, $table_user u 
        WHERE b.user_id = u.id $status $search $order_by $limit_start$limit";

        return $this->get_list($query);
    }
    public function exec_search_bank_by_id_user($search, $limit_start, $limit, $status, $id_user)
    {
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $order_by = $status == "ALL" ? "ORDER BY FIELD(b.status, 'W','S','F')" : "ORDER BY time_created DESC";
        $status = $status == "ALL" ? "" : "AND b.status = '$status'";
        $search = (!empty($search)) ? "AND b.type LIKE '%$search%'" : "";
        $id_user = "AND u.id = $id_user";
        $table = "bank";
        $table_user = "user";
        $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.status, b.time_created, u.name, b.comment
        FROM $table b, $table_user u 
        WHERE b.user_id = u.id $status $id_user $search $order_by $limit_start$limit";

        return $this->get_list($query);
    }
    public function check_bank_exist($id_bank)
    {
        return $this->exec_num_rows("id=$id_bank") > 0 ? true : false;
    }
}
