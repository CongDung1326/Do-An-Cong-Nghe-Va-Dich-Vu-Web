<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class Bank extends Api
{
    private $db, $api;
    public function __construct()
    {
        $this->db = new DB();
        $this->api = new Api();
    }
    public function GetAllBank($limit_start, $limit, $status)
    {
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if ($status != 'F' && $status != 'S' && $status != 'ALL' && $status != 'W') return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "status vui lòng phải là S|W|F"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $order_by = $status == "ALL" ? "ORDER BY FIELD(b.status, 'W','S','F')" : "ORDER BY time_created DESC";
        $status = $status == "ALL" ? "" : "AND b.status = '$status'";
        $table = "bank";
        $table_user = "user";
        $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.status, b.time_created, u.name , u.username, u.id as id_user
        FROM $table b, $table_user u 
        WHERE b.user_id = u.id $status $order_by $limit_start$limit";

        $banks = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "banks" => $banks
            ]);
        } else {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Không có đơn nạp tiền nào",
                "banks" => []
            ]);
        }
    }
    public function GetAllBankByIdUser($search, $limit_start, $limit, $status, $id_user)
    {
        $search = check_string($search);
        $query_user = "SELECT * FROM user WHERE id=$id_user";
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if ($status != 'F' && $status != 'S' && $status != 'ALL' && $status != 'W') return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "status vui lòng phải là S|W|F"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Không tìm thấy user"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $order_by = $status == "ALL" ? "ORDER BY FIELD(b.status, 'W','S','F')" : "ORDER BY time_created DESC";
        $status = $status == "ALL" ? "" : "AND b.status = '$status'";
        $search = (!empty($search)) ? "AND b.type LIKE '%$search%'" : "";
        $table = "bank";
        $table_user = "user";
        $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.status, b.time_created, u.name, b.comment
        FROM $table b, $table_user u 
        WHERE b.user_id = u.id $status $search $order_by $limit_start$limit";

        $banks = $this->db->get_list($query);
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "banks" => $banks
            ]);
        } else {
            return json_encode([
                "errCode" => 0,
                "status" => "success",
                "message" => "Không có đơn nạp tiền nào",
                "banks" => []
            ]);
        }
    }
    public function Deposit($id_user, $id_bank, $status)
    {
        $table = "bank";
        $table_user = "user";
        $query = "SELECT * FROM $table WHERE id=$id_bank AND status='W'";
        $query_user = "SELECT * FROM $table_user WHERE id=$id_user";

        if (empty($id_bank) || empty($id_user) || empty($status)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_user) || !is_numeric($id_bank)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "id tham số truyền vào vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy đơn nạp thẻ nào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy người dùng nào"]);
        if ($status != "S" && $status != "F") return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Trạng thái vui lòng phải là S|F"]);

        $this->db->update($table, [
            "status" => $status
        ], "id=$id_bank");

        if ($status == "S") {
            $bank = $this->db->get_row("SELECT * FROM $table WHERE id=$id_bank AND status='S'");
            $user = $this->db->get_row($query_user);

            $this->db->update($table_user, [
                "money" => $user['money'] + $bank['amount']
            ], "id=$id_user");
        }

        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xử lý dữ liệu thành công",
        ]);
    }
    public function AddDeposit($id_user, $card_type, $money_type, $serial, $pin)
    {
        $table = "bank";
        $query_user = "SELECT * FROM user WHERE id=$id_user";
        if (empty($id_user) || empty($card_type) || empty($money_type) || empty($serial) || empty($pin)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_user)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "id vui lòng phải là số"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Không tìm thấy người dùng"]);
        if (check_types($card_type) != "card-type") return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Vui lòng nhập đúng định dạng thẻ"]);
        if (check_types($money_type) != "card-money") return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng nhập đúng định dạng tiền"]);
        if (strlen($serial) < 10 || strlen($pin) < 10) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Mã thẻ và số serial không hợp lệ"]);

        $this->db->insert($table, [
            "type" => $card_type,
            "serial" => $serial,
            "amount" => $money_type * discount(site("discount")),
            "pin" => $pin,
            "status" => "W",
            "user_id" => $id_user,
            "time_created" => time()
        ]);
        return json_encode([
            "errCode" => 0,
            "status" => "success",
            "message" => "Nạp thẻ thành công",
        ]);
    }
}
