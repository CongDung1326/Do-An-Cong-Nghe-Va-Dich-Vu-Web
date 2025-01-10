<?php
require_once __DIR__ . "/../../config.php";

class NotificationDB extends DB
{
    private $table = "notification_buy";
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
    public function money_spent_user($id_user)
    {
        return $this->exec_select_one("SUM(money) as result", "user_id = $id_user")['result'];
    }
    public function sum_buyed()
    {
        return $this->exec_select_one("COUNT(*) as buyed", null)['buyed'];
    }
    public function sum_sold()
    {
        return $this->exec_select_one("SUM(money) as money", null)['money'];
    }
    public function check_notification_exist($id_notification)
    {
        return $this->exec_num_rows("id=$id_notification") > 0 ? true : false;
    }
    public function get_unique_code($id_notification)
    {
        return $this->exec_select_one("unique_code", "id=$id_notification")['unique_code'];
    }
    public function exec_search_notification($limit_start, $limit)
    {
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $table = "notification_buy";
        $query = "SELECT * FROM $table ORDER BY time DESC $limit_start$limit";

        return $this->get_list($query);
    }
    public function exec_search_random($search, $limit_start, $limit, $id_user, $is_show)
    {
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $limit = ($limit != 0) ? ",$limit" : "";

        $search = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR s.title LIKE '%$search%')" : "";
        $is_show = $is_show == "ALL" ? "" : "AND is_show = '$is_show'";
        $query = "SELECT b.id, b.amount, b.money, b.user_id, b.unique_code, b.time, s.title as title, u.name, b.is_show
        FROM notification_buy b, store_account_children s, user u
        WHERE b.store_account_children_id = s.id AND b.user_id = u.id AND b.user_id = $id_user $search $is_show
        ORDER BY b.time DESC $limit_start$limit";

        return $this->get_list($query);
    }
    public function exec_search_lol($search, $limit_start, $limit, $id_user, $is_show)
    {
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $limit = ($limit != 0) ? ",$limit" : "";

        $search = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR l.id LIKE '%$search%')" : "";
        $is_show = $is_show == "ALL" ? "" : "AND is_show = '$is_show'";
        $query = "SELECT b.id, b.money, b.time, l.number_char, l.number_skin, i.name as rank, l.id as number_account, b.unique_code
        FROM notification_buy b, account_lol l, images i
        WHERE b.account_lol_id = l.id AND b.user_id = $id_user AND l.rank_lol_id = i.id $search $is_show
        ORDER BY b.time DESC $limit_start$limit";

        return $this->get_list($query);
    }
}
