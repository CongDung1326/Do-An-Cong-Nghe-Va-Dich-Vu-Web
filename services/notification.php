<?php
require_once __DIR__ . "/../config.php";

class Notification
{
    private $db, $db_notification, $db_account, $db_user, $err_code = 0;
    public function __construct()
    {
        $this->db = new DB();
        $this->db_notification = new NotificationDB();
        $this->db_account = new AccountDB();
        $this->db_user = new UserDB();
    }
    public function GetAllNotification($limit_start, $limit, $search)
    {
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];

        $notifications = $this->db_notification->exec_search_notification($limit_start, $limit);
        $result = [];
        $result_query = "";

        foreach ($notifications as $notification) {
            if (isset($notification['account_lol_id'])) {
                $id_lol = $notification['account_lol_id'];
                $result_query = $this->db_account->exec_notification_account($search, "lol", $id_lol, "");

                if ($result_query)
                    $result_query['title'] = "Acc Liên Minh #" . $result_query['title'];
            } else {
                $id_product = $notification['store_account_children_id'];
                $id_notification = $notification['id'];
                $result_query = $this->db_account->exec_notification_account($search, "random", $id_product, $id_notification);
            }

            if ($result_query) array_push($result,  $result_query);
        }
        if (count($notifications) > 0) {
            return ["err_code" => $this->err_code, "data" => $result];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function GetAllNotificationRandom($search, $limit_start, $limit, $id_user, $is_show)
    {
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];
        if (empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if ($is_show != "ALL" && $is_show != "T" && $is_show != "F") return ["err_code" => $this->err_code = 37];

        $notifications = $this->db_notification->exec_search_random($search, $limit_start, $limit, $id_user, $is_show);

        if (count($notifications) > 0) {
            return ["err_code" => $this->err_code, "data" => $notifications];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function GetAllNotificationLOL($search, $limit_start, $limit, $id_user, $is_show)
    {
        if (!is_numeric($limit)) return ["err_code" => $this->err_code = 11];
        if (!is_numeric($limit_start)) return ["err_code" => $this->err_code = 12];
        if ($limit < 0) return ["err_code" => $this->err_code = 13];
        if ($limit_start < 0) return ["err_code" => $this->err_code = 14];
        if ($limit_start == 0 && $limit != 0) return ["err_code" => $this->err_code = 15];
        if (empty($id_user)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if ($is_show != "ALL" && $is_show != "T" && $is_show != "F") return ["err_code" => $this->err_code = 37];

        $notifications = $this->db_notification->exec_search_lol($search, $limit_start, $limit, $id_user, $is_show);

        if (count($notifications) > 0) {
            return ["err_code" => $this->err_code, "data" => $notifications];
        } else {
            return ["err_code" => $this->err_code = 22, "data" => []];
        }
    }
    public function RemoveNotificationByIdUser($id_user, $id_notification)
    {
        if (empty($id_user) || empty($id_notification)) return ["err_code" => $this->err_code = 1];
        if (!$this->db_user->check_user_exist($id_user)) return ["err_code" => $this->err_code = 10];
        if (!$this->db_notification->check_notification_exist($id_notification)) return ["err_code" => $this->err_code = 28];

        $this->db_notification->exec_update([
            "is_show" => "F"
        ], "id = $id_notification AND user_id = $id_user");
        return ["err_code" => $this->err_code];
    }
    public function RemoveNotification($id_notification)
    {
        $table_lol = "account_lol";

        if (empty($id_notification)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($id_notification)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_notification->check_notification_exist($id_notification)) return ["err_code" => $this->err_code = 28];

        $notification = $this->db_notification->exec_select_one("", "id=$id_notification");
        $unique_code = $notification['unique_code'];
        $where_account = "unique_code='$unique_code'";

        if ($this->db_account->exec_num_rows("", $where_account) >= 2) {
            $accounts = $this->db_account->exec_select_all("", $where_account);

            foreach ($accounts as $value) {
                $this->db_account->exec_remove("id={$value['id']}");
            }
            $this->db_notification->exec_remove("id=$id_notification");
        } else {
            $account = $this->db_account->exec_select_one("", $where_account);

            $this->db_notification->exec_remove("id=$id_notification");
            $this->db->remove($table_lol, "account_id={$account['id']}");
            $this->db_account->exec_remove("id={$account['id']}");
        }

        return ["err_code" => $this->err_code];
    }
}
