    <?php
    require_once __DIR__ . "/../../config.php";

    class ProductDB extends DB
    {
        private $table = "store_account_children";
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
        public function check_product_exist($id_product)
        {
            return $this->exec_num_rows("id=$id_product") > 0 ? true : false;
        }
        public function exec_get_all_product()
        {
            $table = "store_account_children";
            $table_category = "store_account_parent";
            $query = "SELECT p.id, p.title, p.comment, p.store, p.sold, p.price, p.time_created, p.store_account_parent_id, c.name FROM $table p, $table_category c WHERE c.id = p.store_account_parent_id";

            return $this->get_list($query);
        }
        public function check_title_exist($title)
        {
            return $this->exec_num_rows("title='$title'") > 0 ? true : false;
        }
        public function check_title_bigger_one_exist($title)
        {
            return $this->exec_num_rows("title='$title'") > 1 ? true : false;
        }
    }
