<?php
session_start();

include "./page.php";
include "./model.php";

$API = new API();
// 支払い画面を表示する。
if (isset($_POST["select"])) {
    $_SESSION["payment"] = $_POST["select"];
    $Page = new View(
        "payment",
        $API->get_info($_SESSION["id"], $_POST["select"])
    );
}
// 完了画面表示
elseif (isset($_POST["fin"])) {
    if ($API->payment($_SESSION["id"], $_SESSION["payment"])) {
        $Page = new View("fin", ["id" => $_SESSION["id"]]);
    } else {
        exit("支払い失敗です。");
    }
}
// 支払い方法を選択する画面
elseif (isset($_GET["id"])) {
    $_SESSION["id"] = $_GET["id"];
    $Page = new View("check_user", ["id" => $_GET["id"]]);
}
// 集計画面の表示
// `<自分のサイトのURL>?summary=1`にアクセスすることで表示される。
elseif (isset($_GET["summary"])) {
    $Page = new View("summary", $API->summary());
} else {
    exit("エラーです。カードを再度読み込んでください。");
}
?>