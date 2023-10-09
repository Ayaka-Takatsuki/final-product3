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
// ユーザー名をセッションから取得
$user_name = $_SESSION['name'];


//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_an_table_fn( name, genre, prodname, price, img, shopname, address, remarks, indate ) VALUES(:name, :genre, :prodname, :price, :img, :shopname, :address, :remarks, sysdate())");
$stmt->bindValue(':name', $user_name);
$stmt->bindValue(':genre',   $genre);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':prodname',  $prodname);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':price', $price);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':img', $img);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':shopname', $shopname);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':address', $address);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':remarks', $remarks);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  echo "新規登録情報がリストに追加されました"; // 成功メッセージを表示
  header("refresh:1;url=select.php"); // 1秒後にselect.phpにリダイレクト
}


?>
