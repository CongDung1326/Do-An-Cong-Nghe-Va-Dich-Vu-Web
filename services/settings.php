<?php
require_once __DIR__ . "/../config.php";

class Settings
{
    private $db, $db_settings, $db_user, $db_notification, $db_account, $err_code = 0;
    public function __construct()
    {
        $this->db = new DB();
        $this->db_settings = new SettingsDB();
        $this->db_user = new UserDB();
        $this->db_notification = new NotificationDB();
        $this->db_account = new AccountDB();
    }
    public function GetAllSettings()
    {
        $settings = $this->db_settings->exec_select_all(null, null);
        if (count($settings) > 0) {
            return ["err_code" => $this->err_code, "data" => $settings];
        } else {
            return ["err_code" => $this->err_code = 26];
        }
    }
    public function EditSettings($id_user, $title, $description, $logo, $keyword, $name_shop, $discount)
    {
        $title = check_string($title);
        $description = check_string($description);
        $keyword = check_string($keyword);
        $name_shop = check_string($name_shop);

        if (empty($id_user) || empty($title) || empty($description) || empty($keyword) || empty($name_shop) || empty($discount)) return ["err_code" => $this->err_code = 1];
        if (!is_numeric($discount)) return ["err_code" => $this->err_code = 27];
        if (!is_numeric($id_user)) return ["err_code" => $this->err_code = 9];
        if (!$this->db_user->check_admin($id_user)) return ["err_code" => $this->err_code = 10];

        $result = [
            "title" => $title,
            "description" => $description,
            "keyword" => $keyword,
            "name_shop" => $name_shop,
            "discount" => $discount,
        ];
        if (!empty($logo)) $result['logo'] = $logo;
        foreach ($result as $key => $value) {
            $this->db_settings->exec_update([
                "value" => $value
            ], "name = '$key'");
        }

        return ["err_code" => $this->err_code];
    }
    public function GetDataBanner()
    {
        $buyed = $this->db_notification->sum_buyed();
        $account_sold = $this->db_account->sum_account_sold();
        $count_user = $this->db_user->sum_user();
        $sold = $this->db_notification->sum_sold();

        $data = [
            "buyed" => $buyed,
            "account_sold" => $account_sold,
            "count_user" => $count_user,
            "sold" => $sold
        ];

        return ["err_code" => $this->err_code, "data" => $data];
    }
}
