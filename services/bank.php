<?php
require_once __DIR__ . "/../config.php";

class Bank
{
    private $db, $db_bank, $db_user, $err_code = 0;
    public function __construct()
    {
        $this->db = new DB();
        $this->db_bank = new BankDB();
        $this->db_user = new UserDB();
    }
    public function GetAllBank($limit_start, $limit, $search, $status)
    {
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];
        if ($status != 'F' && $status != 'S' && $status != 'ALL' && $status != 'W') return ["err_code" => $this->err_code = 38];

        $banks = $this->db_bank->exec_search_bank($limit_start, $limit, $search, $status);
        if (count($banks) > 0) {
            return ["err_code" => $this->err_code, "data" => $banks];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function GetAllBankByIdUser($search, $limit_start, $limit, $status, $id_user)
    {
        $search = check_string($search);
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];
        if ($status != 'F' && $status != 'S' && $status != 'ALL' && $status != 'W') return ["err_code" => $this->err_code = 38];
        if (empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];

        $banks = $this->db_bank->exec_search_bank_by_id_user($search, $limit_start, $limit, $status, $id_user);
        if (count($banks) > 0) {
            return ["err_code" => $this->err_code, "data" => $banks];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function Deposit($id_user, $id_bank, $status)
    {
        if (empty($id_bank) || empty($id_user) || empty($status)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_user) || !is_numeric($id_bank)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_bank->check_bank_exist($id_bank)) return ["err_code" => $this->err_code = 39];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if ($status != "S" && $status != "F") return ["err_code" => $this->err_code = 40];

        $this->db_bank->exec_update([
            "status" => $status
        ], "id=$id_bank");

        if ($status == "S") {
            $bank = $this->db_bank->exec_select_one("", "id=$id_bank AND status='S'");
            $user = $this->db_user->exec_select_one("", "id=$id_user");

            $this->db_user->exec_update([
                "money" => $user['money'] + $bank['amount']
            ], "id=$id_user");
        }

        return ["err_code" => $this->err_code];
    }
    public function AddDeposit($id_user, $card_type, $money_type, $serial, $pin)
    {
        if (empty($id_user) || empty($card_type) || empty($money_type) || empty($serial) || empty($pin)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if (check_types($card_type) != "card-type") return ["err_code" => $this->err_code = 41];
        if (check_types($money_type) != "card-money") return ["err_code" => $this->err_code = 42];
        if (strlen($serial) < 10 || strlen($pin) < 10) return ["err_code" => $this->err_code = 43];

        $this->db_bank->exec_insert([
            "type" => $card_type,
            "serial" => $serial,
            "amount" => $money_type * discount(site("discount")),
            "pin" => $pin,
            "status" => "W",
            "user_id" => $id_user,
            "time_created" => time()
        ]);
        return ["err_code" => $this->err_code];
    }
}
