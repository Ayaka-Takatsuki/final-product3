<?php
ini_set('display_errors', '1');
session_start();
include("funcs.php");
$pdo = db_conn();
//1. POSTデータ取得
$genre   = $_POST["genre"];
$prodname  = $_POST["prodname"];
$price = $_POST["price"];
$img  = fileUpload("upfile","upload");//ここが変かも
$shopname = $_POST["shopname"];
$address = $_POST["address"];
$remarks = $_POST["remarks"];
$id     = $_POST["id"];

//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE gs_an_table_fn SET genre=:genre, prodname=:prodname, price=:price, img=:img, shopname=:shopname, address=:address, remarks=:remarks WHERE id=:id");
$stmt->bindValue(':genre',   $genre,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':prodname',  $prodname,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':price', $price, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':img', $img, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':shopname', $shopname, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':address', $address, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  redirect("index.php");
}
?>
