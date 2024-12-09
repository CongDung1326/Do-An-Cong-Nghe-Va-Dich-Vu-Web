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
    public function GetAllBank($limit_start = 0, $limit = 0, $status = 'ALL', $data)
    {
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if ($status != 'F' && $status != 'S' && $status != 'ALL' && $status != 'W') return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "status vui lòng phải là S|W|F"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $order_by = $status == "ALL" ? "ORDER BY FIELD(b.status, 'W','S','F')" : "ORDER BY time_created DESC";
        $status = $status == "ALL" ? "" : "AND b.status = '$status'";
        $table = "bank";
        $table_user = "user";
        $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.status, b.time_created, u.name 
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
    public function GetAllBankByIdUser($search, $limit_start = 0, $limit = 0, $status = 'ALL', $id_user, $data)
    {
        $search = check_string($search);
        $query_user = "SELECT * FROM user WHERE id=$id_user";
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if ($status != 'F' && $status != 'S' && $status != 'ALL' && $status != 'W') return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "status vui lòng phải là S|W|F"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 10, "status" => "error", "message" => "Không tìm thấy user"]);

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
}
