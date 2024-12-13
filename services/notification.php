<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/api.php";

class Notification extends Api
{
    private $db, $api;
    public function __construct()
    {
        $this->db = new DB();
        $this->api = new Api();
    }
    public function GetAllNotification($limit_start = 0, $limit = 0, $search)
    {
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);

        $limit = ($limit != 0) ? ",$limit" : "";
        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $table = "notification_buy";
        $query = "SELECT * FROM $table ORDER BY time DESC $limit_start$limit";
        $search_lol = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR l.id LIKE '%$search%')" : "";
        $query_lol = "SELECT b.id, b.amount, b.money, b.user_id, b.unique_code, b.time, l.id as title, u.name
        FROM notification_buy b, account_lol l, user u
        WHERE b.account_lol_id = l.id AND b.user_id = u.id $search_lol ";
        $search_random = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR s.title LIKE '%$search%')" : "";
        $query_product = "SELECT b.id, b.amount, b.money, b.user_id, b.unique_code, b.time, s.title as title, u.name
        FROM notification_buy b, store_account_children s, user u
        WHERE b.store_account_children_id = s.id AND b.user_id = u.id $search_random ";
        $notifications = $this->db->get_list($query);
        $result = [];
        $result_query = "";

        foreach ($notifications as $notification) {
            if (isset($notification['account_lol_id'])) {
                $id_lol = $notification['account_lol_id'];
                $result_query = $this->db->get_row($query_lol . "AND l.id=$id_lol");

                if ($result_query)
                    $result_query['title'] = "Acc Liên Minh #" . $result_query['title'];
            } else {
                $id_product = $notification['store_account_children_id'];
                $result_query = $this->db->get_row($query_product . "AND s.id=$id_product");
            }

            if ($result_query) array_push($result,  $result_query);
        }
        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "notifications" => $result
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Thông báo đang trống",
                "notifications" => []
            ]);
        }
    }
    public function GetAllNotificationRandom($search = "", $limit_start = 0, $limit = 0, $id_user, $is_show = "ALL", $data)
    {
        $table_user = "user";
        $query_user = "SELECT * FROM $table_user WHERE id = $id_user";
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Không tìm thấy user"]);
        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if ($is_show != "ALL" && $is_show != "T" && $is_show != "F") return json_encode_utf8(["errCode" => 10, "status" => "error", "message" => "Is show vui lòng phải là T|F"]);

        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $limit = ($limit != 0) ? ",$limit" : "";

        $search = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR s.title LIKE '%$search%')" : "";
        $is_show = $is_show == "ALL" ? "" : "AND is_show = '$is_show'";
        $query = "SELECT b.id, b.amount, b.money, b.user_id, b.unique_code, b.time, s.title as title, u.name, b.is_show
        FROM notification_buy b, store_account_children s, user u
        WHERE b.store_account_children_id = s.id AND b.user_id = u.id AND b.user_id = $id_user $search $is_show
        ORDER BY b.time DESC $limit_start$limit";
        $notifications = $this->db->get_list($query);

        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "notifications" => $notifications
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Thông báo đang trống",
                "notifications" => []
            ]);
        }
    }
    public function GetAllNotificationLOL($search = "", $limit_start = 0, $limit = 0, $id_user, $is_show = "ALL", $data)
    {
        $table_user = "user";
        $query_user = "SELECT * FROM $table_user WHERE id = $id_user";
        if (!is_numeric($limit)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Limit vui lòng phải là số"]);
        if (!is_numeric($limit_start)) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Limit start vui lòng phải là số"]);
        if ($limit < 0) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Limit vui lòng phải lớn hơn 0"]);
        if ($limit_start < 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Limit start vui lòng phải lớn hơn 0"]);
        if ($limit_start == 0 && $limit != 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Vui lòng set limit start lớn hơn 0"]);
        if (empty($id_user)) return json_encode_utf8(["errCode" => 6, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 7, "status" => "error", "message" => "Không tìm thấy user"]);
        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 8, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 9, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if ($is_show != "ALL" && $is_show != "T" && $is_show != "F") return json_encode_utf8(["errCode" => 10, "status" => "error", "message" => "Is show vui lòng phải là T|F"]);

        $limit_start = ($limit_start != 0) ? "LIMIT $limit_start" : "";
        $limit = ($limit != 0) ? ",$limit" : "";

        $search = (!empty($search)) ? "AND (b.unique_code LIKE '%$search%' OR l.id LIKE '%$search%')" : "";
        $is_show = $is_show == "ALL" ? "" : "AND is_show = '$is_show'";
        $query = "SELECT b.id, b.money, b.time, l.number_char, l.number_skin, i.name as rank, l.id as number_account, b.unique_code
        FROM notification_buy b, account_lol l, images i
        WHERE b.account_lol_id = l.id AND b.user_id = $id_user AND l.rank_lol_id = i.id $search $is_show
        ORDER BY b.time DESC $limit_start$limit";
        $notifications = $this->db->get_list($query);

        if ($this->db->num_rows($query) > 0) {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Lấy dữ liệu thành công",
                "notifications" => $notifications
            ]);
        } else {
            return json_encode_utf8([
                "errCode" => 0,
                "status" => "success",
                "message" => "Thông báo đang trống",
                "notifications" => []
            ]);
        }
    }
    public function RemoveNotificationByIdUser($id_user, $id_notification, $data)
    {
        $table = "notification_buy";
        $table_user = "user";
        $query_user = "SELECT * FROM $table_user WHERE id = $id_user";
        $query_notification = "SELECT * FROM $table WHERE id = $id_notification";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_user) || empty($id_notification)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if ($this->db->num_rows($query_user) == 0) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "Không tìm thấy người dùng"]);
        if ($this->db->num_rows($query_notification) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy đơn hàng nào cả"]);

        $this->db->update($table, [
            "is_show" => "F"
        ], "id = $id_notification AND user_id = $id_user");
        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xoá thành công",
        ]);
    }
    public function RemoveNotification($id_notification, $data)
    {
        $table = "notification_buy";
        $table_account = "account";
        $table_lol = "account_lol";
        $query = "SELECT * FROM $table WHERE id=$id_notification";

        if (!isset($data->username) || !isset($data->password)) return json_encode_utf8(["errCode" => 1, "status" => "error", "message" => "Bạn không đủ quyền hạn để truy cập"]);
        if (!$this->api->CheckIsAdmin($data->username, hash_encode($data->password))) return json_encode_utf8(["errCode" => 2, "status" => "error", "message" => "Đăng nhập thất bại"]);
        if (empty($id_notification)) return json_encode_utf8(["errCode" => 3, "status" => "error", "message" => "Thiếu tham số truyền vào"]);
        if (!is_numeric($id_notification)) return json_encode_utf8(["errCode" => 4, "status" => "error", "message" => "id truyền vào vui lòng phải là số"]);
        if ($this->db->num_rows($query) == 0) return json_encode_utf8(["errCode" => 5, "status" => "error", "message" => "Không tìm thấy đơn hàng nào"]);

        $notification = $this->db->get_row($query);
        $unique_code = $notification['unique_code'];
        $query_account = "SELECT * FROM $table_account WHERE unique_code='$unique_code'";

        if ($this->db->num_rows($query_account) >= 2) {
            $accounts = $this->db->get_list($query_account);

            foreach ($accounts as $value) {
                $this->db->remove($table_account, "id={$value['id']}");
            }
            $this->db->remove($table, "id=$id_notification");
        } else {
            $account = $this->db->get_row($query_account);

            $this->db->remove($table, "id=$id_notification");
            $this->db->remove($table_lol, "account_id={$account['id']}");
            $this->db->remove($table_account, "id={$account['id']}");
        }

        return json_encode_utf8([
            "errCode" => 0,
            "status" => "success",
            "message" => "Xoá thành công",
        ]);
    }
}
