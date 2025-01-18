<?php
require_once __DIR__ . "/../config.php";

class AccountLol
{
    private $db_account_lol, $err_code = 0;

    public function __construct()
    {
        $this->db_account_lol = new AccountLolDB();
    }

    public function GetAccountLolByIdAccount($id_account)
    {
        if (empty($id_account)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_account)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_account_lol->check_account_lol_exist($id_account)) return ["err_code" => $this->err_code = 32];

        $account = $this->db_account_lol->exec_select_one("", "account_id=$id_account");
        $this->db_account_lol->dis_connect();
        return ["err_code" => $this->err_code, "data" => $account];
    }
}
