<?php
ini_set('display_errors', '1');
//2. DB接続します
include("funcs.php");
$pdo = db_conn();

//1. POSTデータ取得
$genre = $_POST["genre"];
$prodname = $_POST["prodname"];
$price = $_POST["price"];
$current_img = $_POST["current_img"]; // 既存の画像ファイル名を取得

// 新しい画像のアップロードを試み、成功すれば新しい画像ファイル名、失敗すれば元の画像ファイル名を取得
if (!empty($_FILES["upfile"]["name"])) { // 新しい画像が選択されているかを確認
  $img = fileUpload("upfile", "upload");
  if ($img === "ファイル取得エラー" || $img === "ファイル移動に失敗") {
    $img = $current_img; // アップロードに失敗した場合、元の画像ファイル名を使用
  } else {
    // 新しい画像がアップロード成功した場合、既存の画像ファイルを削除
    $currentImage = "upload/" . $current_img;
    if (file_exists($currentImage)) {
      unlink($currentImage);
    }
  }
} else {
  // 新しい画像が選択されていない場合、既存の画像ファイル名を使用
  $img = $current_img;
}

$shopname = $_POST["shopname"];
$address = $_POST["address"];
$remarks = $_POST["remarks"];
$id     = $_POST["id"];



//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE gs_an_table_fn SET genre=:genre, prodname=:prodname, price=:price, img=:img, shopname=:shopname, address=:address, remarks=:remarks, indate=sysdate() WHERE id=:id");
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
  redirect("select.php");
}
