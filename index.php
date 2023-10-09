<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>データ登録</title>
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
  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header"><a class="navbar-brand" href="select.php">データ一覧</a></div>
      </div>
    </nav>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <!-- Main[Start] -->
  <form method="POST" action="insert.php" enctype="multipart/form-data">
    <div class="jumbotron">
      <fieldset>
        <legend>新規登録</legend>
        <label>ジャンル：<input type="text" name="genre" value="<?= $row["genre"] ?>"></label><br>
        <label>商品：<input type="text" name="prodname" value="<?= $row["prodname"] ?>"></label><br>
        <label>税込金額：¥<input type="text" name="price" value="<?= $row["price"] ?>"></label><br>
        <label>購入店：<input type="text" name="shopname" value="<?= $row["shopname"] ?>"></label><br>
        <label>住所：<input type="text" name="address" value="<?= $row["address"] ?>"></label><br>
        <label>備考：<textArea name="remarks" rows="4" cols="40"><?= $row["remarks"] ?></textArea></label><br>
        <label>写真：<input type="file" name="upfile"></label><br>
        <input type="submit" value="送信">
        <input type="hidden" name="id" value="<?= $id ?>">
      </fieldset>
    </div>
  </form>
  <!-- Main[End] -->



</body>

</html>