<?php
class View
{
    function __construct(string $type, array $data)
    {
        echo $this->main_view($this->$type($data));
    }

    // 基本となるViewで{$contents}の部分に固有の要素を組み込み表示する。
    function main_view($contents = null)
    {
        $data = date("ymdhis");
        return <<<EOF
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>kakera-lab</title>
<meta name="description" content="決算システム">
<link rel="stylesheet" href="../style.css?{$data}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
{$contents}
</body>
</html>
EOF;
    }

    // ユーザー確認と支払い方法の選択画面
    function check_user(array $data): string
    {
        $id = $data["id"];
        return <<<EOF
<form method="post" action="">
    <div class="view">
    {$this->card($id)}
    </div>
    <div><button class="button" type="submit" name="select" value=cash>現金</button></div>
    <div><button class="button" type="submit" name="select" value=paypay>PayPay</button></div>
</form>
EOF;
    }

    // 支払い法カード描画ページ
    function payment(array $data): string
    {
        $payment = $data["payment"];
        $total = $data["total"];
        return <<<EOF
<form method="post" action="">
    <div class="view">
    {$this->card_payment($payment, $total)}
    </div>
    <div><button class="button" type="submit" name="fin" value=1>支払い完了</button></div>
    <div><button class="button" type="button" onclick="location.href='./index.php'">戻る</button></div>
</form>
EOF;
    }

    // 支払い完了ページ
    function fin(array $data): string
    {
        return <<<EOF
<div class="view">
{$this->card_payment("fin", "")}
</div>
EOF;
    }

    // カードを描画する
    protected function card(string $id): string
    {
        return <<<EOF
\t<div class="box"><img class="card" src="../image/{$id}.png" alt="no image"/></div>\n
EOF;
    }

    // 支払い法カードを取得して表示する画面
    protected function card_payment(string $payment, string $total): string
    {
        if ($payment === "cash") {
            return <<<EOF
\t<div class="box"><img class="card" src="./image/cash/{$total}.png" alt="no image"/></div>\n
EOF;
        } elseif ($payment === "paypay") {
            return <<<EOF
\t<div class="box"><img class="card" src="./image/paypay/{$total}.png" alt="no image"/></div>\n
EOF;
        } elseif ($payment === "fin") {
            return <<<EOF
\t<div class="box"><img class="card" src="./image/fin.png" alt="no image"/></div>\n
EOF;
        }
    }

    // 支払い状況を確認する画面
    protected function summary(array $data): string
    {
        $finish = "";
        $notyet = "";
        foreach ($data["finish"] as $value) {
            $finish .= $value . "<br/>";
        }
        foreach ($data["notyet"] as $value) {
            $notyet .= "・" . $value . "<br/>";
        }
        $num_finish = count($data["finish"]);
        $num_notyet = count($data["notyet"]);
        $num_total = $num_finish + $num_notyet;
        return <<<EOF
<div class="view">
    <h3>集金状況</h3>
    ・集金済: {$data["total_finish"]} / {$data["total"]}<br/>
    (現金:{$data["total_finish_cash"]} / PayPay:{$data["total_finish_paypay"]})<br/>
    ・残り: {$data["total_notyet"]}<br/>
    <h3>未納者: {$num_notyet} / {$num_total}</h3>
    {$notyet}
    <h3>支払い済: {$num_finish} / {$num_total}</h3>
    {$finish}
</div>
EOF;
    }
}
?>
