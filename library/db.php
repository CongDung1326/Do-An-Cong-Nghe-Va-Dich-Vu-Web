<?php
include_once __DIR__ . "/env.php";

class DB
{
    private $db;

    public function connect()
    {
        if (!$this->db) {
            $this->db = mysqli_connect(DB_HOST, DB_NAME, DB_PASSWORD, DB_TABLE, DB_PORT);
            $this->db->set_charset("utf8");
        }
    }

    public function dis_connect()
    {
        if ($this->db) {
            mysqli_close($this->db);
        }
    }

    public function site($data)
    {
        $this->connect();
        $row = $this->db->query("SELECT * FROM settings WHERE name='$data'")->fetch_array();
        $this->dis_connect();
        return $row['value'];
    }

    public function insert($table, $data)
    {
        $this->connect();
        $field_list = "";
        $value_list = "";

        foreach ($data as $key => $value) {
            $field_list .= ",$key";
            $value_list .= ",'" . mysqli_real_escape_string($this->db, $value) . "'";
        }
        $query = "INSERT INTO $table (" . trim($field_list, ",") . ") VALUES (" . trim($value_list, ",") . ")";
        return mysqli_query($this->db, $query);
    }

    public function update($table, $data, $where)
    {
        $this->connect();
        $query = "";

        foreach ($data as $key => $value) {
            $query .= $key . "='" . mysqli_real_escape_string($this->db, $value) . "',";
        }
        $query = "UPDATE $table SET " . trim($query, ",") . " WHERE $where";
        $result = mysqli_query($this->db, $query);
        return $result;
    }

    public function remove($table, $where)
    {
        $this->connect();
        $query = "DELETE FROM $table WHERE $where";
        $result = mysqli_query($this->db, $query);
        return $result;
    }

    public function get_list($query)
    {
        $this->connect();
        $result = mysqli_query($this->db, $query);
        if (!$result) {
            die("Wrong query!");
        }

        $return = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($return, $row);
        }
        mysqli_free_result($result);
        return isset($return) ? $return : false;
    }

    public function get_row($query)
    {
        $this->connect();
        $result = mysqli_query($this->db, $query);
        if (!$result) {
            die("Wrong query!");
        }

        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row ? $row : false;
    }

    public function num_rows($query)
    {
        $this->connect();
        $result = mysqli_query($this->db, $query);
        if (!$result) {
            die("Wrong query!");
        }

        $row = mysqli_num_rows($result);
        mysqli_free_result($result);
        return $row ? $row : false;
    }
}
