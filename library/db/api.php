<?php
require_once __DIR__ . "/../../config.php";

class ApiDB extends DB
{
    private $table = "api";
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
}