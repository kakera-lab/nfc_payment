<?php
class API
{
    protected $jsonfile;

    function __construct()
    {
        // DB(Json)のファイルパス
        $this->jsonfile = "./json/db.json";
    }

    // DB(Json)の情報を全取得:完了
    protected function load_json(): array
    {
        $data = file_get_contents($this->jsonfile);
        $data = json_decode($data, true);
        return $data;
    }

    // DB(Json)の情報を全書き込み
    protected function save_json(array $data): bool
    {
        $data = json_encode($data);
        if (file_put_contents($this->jsonfile, $data) === false) {
            return false;
        } else {
            return true;
        }
    }

    // 支払い関する情報を収集する。
    function get_info(string $id, string $payment): array
    {
        $data = $this->load_json();
        return [
            "id" => $id,
            "payment" => $payment,
            "total" => $data[$id]["total"],
        ];
    }

    // 支払い結果を保存する。
    function payment(string $id, string $payment): bool
    {
        $data = $this->load_json();
        if (!isset($data[$id])) {
            return false;
        }
        $data[$id]["payment"] = $payment;
        if ($this->save_json($data)) {
            return true;
        } else {
            return false;
        }
    }

    // 支払い状況を集計する。
    function summary(): array
    {
        $result = [];
        $result["total"] = 0;
        $result["total_finish"] = 0;
        $result["total_finish_cash"] = 0;
        $result["total_finish_paypay"] = 0;
        $result["total_notyet"] = 0;
        $result["finish"] = [];
        $result["notyet"] = [];
        $data = $this->load_json();
        foreach ($data as $value) {
            $result["total"] += $value["total"];
            if ($value["payment"] === "cash") {
                $result["total_finish"] += $value["total"];
                $result["total_finish_cash"] += $value["total"];
                $result["finish"][] =
                    $value["name"] . " (" . $value["total"] . ")";
            } elseif ($value["payment"] === "paypay") {
                $result["total_finish"] += $value["total"];
                $result["total_finish_paypay"] += $value["total"];
                $result["finish"][] =
                    $value["name"] . " (" . $value["total"] . ")";
            } else {
                $result["total_notyet"] += $value["total"];
                $result["notyet"][] =
                    $value["name"] . " (" . $value["total"] . ")";
            }
        }
        return $result;
    }
}
?>
