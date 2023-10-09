<?php
ini_set('display_errors', '1');
require 'vendor/autoload.php'; // PHPSpreadsheetを読み込む
include("funcs.php");

// redirect("select.php");
use PhpOffice\PhpSpreadsheet\IOFactory;

// var_dump($_FILES);

// フォームがサブミットされたかどうかを確認
if (isset($_POST['submit'])) {
    // アップロードされたExcelファイルを受け取る
    $uploadedFile = $_FILES['fileToUpload']['tmp_name'];

// Excelファイルを読み込む
$spreadsheet = IOFactory::load($uploadedFile);//target_fileが設定されてない
$worksheet = $spreadsheet->getActiveSheet();

// カラム:prodname D25セルの値を取得 
$cellValueD25 = $worksheet->getCell('D25')->getValue();
// if (strlen($cellValueF24) >= 6) {
//     $substring = substr($cellValueF24, 5); // 6文字目以降を取得
// } else {
//     $substring = null; // ブランクの場合はデフォルト値としてNULLを設定
// }

// カラム:price E21セルの値を取得 
$cellValueE21 = $worksheet->getCell('E21')->getValue();

// カラム:shopname D17セルの値を取得 
$cellValueD17 = $worksheet->getCell('D17')->getValue();

// カラム:address D16セルの値を取得
$cellValueD16 = $worksheet->getCell('D16')->getValue();

// カラム:remarks D27セルの値を取得
$cellValueD27 = $worksheet->getCell('D27')->getValue();

// カラム:name H10セルの値を取得
$cellValueH10 = $worksheet->getCell('H10')->getValue();

// DB接続
$pdo = db_conn();

// データベースにデータを挿入
$stmt = $pdo->prepare("INSERT INTO gs_an_table_fn (prodname, price, shopname, address, remarks, indate, name) VALUES (:prodname, :price, :shopname, :address, :remarks, sysdate(), :name )");
$stmt->bindValue(':prodname', $cellValueD25);
$stmt->bindValue(':price', $cellValueE21);
$stmt->bindValue(':shopname', $cellValueD17);
$stmt->bindValue(':address', $cellValueD16);
$stmt->bindValue(':remarks', $cellValueD27);
$stmt->bindValue(':name', $cellValueH10);
$status = $stmt->execute(); //実行

// $stmt = $pdo->prepare("UPDATE gs_an_table_fn SET name=:name,email=:email,naiyou=:naiyou WHERE id=:id");
// $stmt->bindValue(':name',   $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':email',  $email,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':naiyou', $naiyou, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':id',     $id,     PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

// //３．データ登録SQL作成
// $stmt = $pdo->prepare("INSERT INTO gs_an_table_fn( name, email, naiyou, img, indate )VALUES(:name, :email, :naiyou, :img, sysdate())");
// $stmt->bindValue(':name', $name);
// $stmt->bindValue(':email', $email);
// $stmt->bindValue(':naiyou', $naiyou);
// $stmt->bindValue(':img', $img);
// $status = $stmt->execute();


// データベースへの挿入が成功したことを確認するためのメッセージ
echo "アップロードされた情報がリストに追加されました";

header("refresh:1;url=select.php");
// 他の処理を追加
}
