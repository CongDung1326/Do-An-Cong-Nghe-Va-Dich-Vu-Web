<?php
require_once __DIR__ . "/../../config.php";

class AccountDB extends DB
{
    private $table = "account";
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
    public function sum_account_sold()
    {
        return $this->exec_select_one("COUNT(*) as sold", "is_sold = 'T'")['sold'];
    }
    public function check_account_has_unique_code($unique_code)
    {
        return $this->exec_num_rows("unique_code='$unique_code'") > 0 ? true : false;
    }
    public function exec_search_random($search, $limit_start, $limit, $id_user, $is_sold, $unique_code)
    {
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND a.username LIKE '%$search%'" : "";
        $id_user = !empty($id_user) ? "AND a.user_id = $id_user" : "";
        $is_sold = $is_sold != "ALL" ? "AND a.is_sold = '$is_sold'" : "";
        $unique_code = !empty($unique_code) ? "AND a.unique_code = '$unique_code'" : "";

        $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, product s 
            WHERE (a.product_id = s.id) $is_sold AND a.type = 'random' $id_user $unique_code $search $limit_start$limit";

        return $this->get_list($query);
    }
    public function exec_search_lol($search, $limit_start, $limit, $id_user, $is_sold, $unique_code)
    {
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND a.username LIKE '%$search%'" : "";
        $id_user = !empty($id_user) ? "AND a.user_id = $id_user" : "";
        $is_sold = $is_sold != "ALL" ? "AND a.is_sold = '$is_sold'" : "";
        $unique_code = !empty($unique_code) ? "AND a.unique_code = '$unique_code'" : "";

        $query = "SELECT a.id, a.username, a.password, l.id as name, a.is_sold, l.number_char, l.number_skin, i.name as rank, i.href, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) $is_sold $id_user $unique_code AND a.type = 'lol' $search $limit_start$limit";

        return $this->get_list($query);
    }
    public function check_account_exist($id_account)
    {
        return $this->exec_num_rows("id=$id_account") > 0 ? true : false;
    }
    public function check_account_username_exist($username)
    {
        return $this->exec_num_rows("username='$username'") > 1 ? true : false;
    }
    public function check_account_is_sold($id_account, $is_sold, $type)
    {
        $is_sold = !empty($is_sold) ? $is_sold : "F";
        $type = !empty($type) ? $type : "random";
        return $this->exec_num_rows("id=$id_account AND is_sold='$is_sold' AND type='$type'") > 0 ? true : false;
    }
    public function exec_update_account_random($username, $password, $id_account, $id_product)
    {
        $old_product = $this->exec_select_one("product_id", "id=$id_account");
        $new_product = $this->get_row("SELECT * FROM product WHERE id=$id_product");
        $id_old_product = $old_product['product_id'];
        $id_new_product = $new_product['id'];
        $get_data_old_product = $this->get_row("SELECT * FROM product WHERE id=$id_old_product");

        if ($id_old_product != $id_new_product) {
            // Update old product
            $this->update("product", [
                "store" => $get_data_old_product['store'] - 1
            ], "id=$id_old_product");
            // Update new product
            $this->update("product", [
                "store" => $new_product['store'] + 1
            ], "id=$id_new_product");
        }

        if ($this->check_account_username_exist($username)) return false;
        $this->exec_update([
            "username" => $username,
            "password" => $password,
            "product_id" => $id_product,
        ], "id=$id_account");
        return true;
    }
    public function exec_get_account_random_have_sold($id_account, $is_sold)
    {
        $table = "account";
        $table_product = "product";
        $is_sold = !empty($is_sold) ? $is_sold : "F";
        $query = "SELECT a.id, a.username, a.password, a.type, s.title 
        FROM $table a, $table_product s 
        WHERE a.product_id = s.id 
        AND a.is_sold='$is_sold' 
        AND a.id=$id_account
        AND a.type='random'";

        return $this->get_row($query);
    }
    public function exec_get_account_lol_have_sold($id_account, $is_sold)
    {
        $table = "account";
        $table_lol = "account_lol";
        $table_rank = "images";
        $is_sold = "AND a.is_sold = '$is_sold'";
        $query = "SELECT a.id, a.username, a.password, l.id as name,a.type , l.rank_lol_id, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM $table a, $table_lol l, $table_rank i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) $is_sold AND a.type = 'lol' AND a.id = $id_account";

        return $this->get_row($query);
    }
    public function exec_search_account_buyed($search, $limit_start, $limit, $id_account)
    {
        $table = "account";
        $table_user = "user";
        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $search = (!empty($search)) ? "AND (a.username LIKE '%$search%' OR a.unique_code LIKE '%$search%')" : "";
        $id_account = !empty($id_account) ? "AND a.id = $id_account" : "";

        $query = "SELECT a.id, a.username, a.password, a.is_sold, u.username as user_username, a.unique_code , a.type
        FROM $table a, $table_user u 
        WHERE a.user_id = u.id AND a.is_sold = 'T' $id_account $search $limit_start$limit";

        return $this->get_list($query);
    }
    public function exec_notification_account($search, $type, $id, $id_notification)
    {
        switch ($type) {
            case "lol":
                $search = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR l.id LIKE '%$search%')" : "";
                $query = "SELECT b.id, b.amount, b.money, b.user_id, b.unique_code, b.time, l.id as title, u.name
                FROM notification_buy b, account_lol l, user u
                WHERE b.account_lol_id = l.id AND b.user_id = u.id $search AND l.id=$id";

                return $this->get_row($query);
            case "random":
                $search = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR s.title LIKE '%$search%')" : "";
                $query = "SELECT b.id, b.amount, b.money, b.user_id, b.unique_code, b.time, s.title as title, u.name
                FROM notification_buy b, product s, user u
                WHERE b.product_id = s.id AND b.user_id = u.id $search AND s.id=$id AND b.id=$id_notification";

                return $this->get_row($query);
        }
    }
}
