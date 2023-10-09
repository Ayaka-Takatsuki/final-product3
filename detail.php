<?php
ini_set('display_errors', '1');
session_start();
$id = $_GET["id"]; //?id~**を受け取る
include("funcs.php"); // funcs.phpをインクルード
sschk(); // 接続確認
$pdo = db_conn(); // データベース接続を確立

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_an_table_fn WHERE id=:id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if ($status == false) {
  sql_error($stmt);
} else {
  $row = $stmt->fetch();
}
?>



<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>データ更新</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/transitions.css"> <!-- 新しいCSSファイルを追加 -->
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body>

  <!-- Head[Start] -->
  <?php include("menu.php"); ?>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <form method="POST" action="update.php" enctype="multipart/form-data">
    <div class="jumbotron">
      <fieldset>
        <legend>詳細編集</legend>
        <label>ジャンル：<input type="text" name="genre" value="<?= $row["genre"] ?>"></label><br>
        <label>商品：<input type="text" name="prodname" value="<?= $row["prodname"] ?>"></label><br>
        <label>税込金額：¥<input type="text" name="price" value="<?= $row["price"] ?>"></label><br>
        <label>店名：<input type="text" name="shopname" value="<?= $row["shopname"] ?>"></label><br>
        <label>住所：<input type="text" name="address" value="<?= $row["address"] ?>"></label><br>
        <label>備考：<textArea name="remarks" rows="4" cols="40"><?= $row["remarks"] ?></textArea></label><br>
        <label>写真：
          <!-- 現在の画像を表示 -->
          <?php
          $currentImage = "upload/" . $row["img"]; // 画像ファイルのパス
          if (file_exists($currentImage)) {
            echo '<img id="preview" src="' . $currentImage . '" width="200" alt="現在の画像">';
          }
          ?>

          <br>

          <!-- 新しい画像アップロードフォーム -->
          <label>新しい写真を選択：<input type="file" name="upfile" onchange="previewImage(this)"></label><br>

          <!-- 新しい画像の選択がない場合でも、既存の画像を保持するための隠しフィールドを追加 -->
          <input type="hidden" name="current_img" value="<?= $row["img"] ?>">
          <?php
          if (!isset($_FILES["upfile"]) || $_FILES["upfile"]["error"] != 0) {
            echo '<script>previewImage(document.querySelector(\'[name="upfile"]\'));</script>';
          }
          ?>


          <br>

          <input type="submit" value="送信">
          <input type="hidden" name="id" value="<?= $id ?>">
        </label>
      </fieldset>
    </div>
  </form>
  <!-- Main[End] -->

  <script>
    function previewImage(input) {
      var preview = document.getElementById('preview');
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>

</body>

</html>