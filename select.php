<?php
ini_set('display_errors', '1');
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

// ２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT id, genre, prodname, price, img, shopname, address, remarks, indate, name FROM gs_an_table_fn");
$status = $stmt->execute();

// ３．データ表示 最後に削除ボタン用thの作成は不要か確認！
$tableHtml = '<table border="1">
                <tr>
                    <th>#</th>
                    <th>ジャンル</th>
                    <th>写真</th>
                    <th>商品</th>
                    <th>税込金額</th>
                    <th>店名</th>
                    <th>購入店</th>
                    <th>備考</th>
                    <th>更新日時</th>
                    <th>更新者</th>
                </tr>';

if ($status == false) {
  sql_error($stmt);
} else {
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tableHtml .= '<tr>';
    $tableHtml .= '<td><a href="detail.php?id=' . $row["id"] . '">' . $row["id"] . '</a></td>';
    $tableHtml .= '<td>' . $row["genre"] . '</td>';
    $tableHtml .= '<td><img src="upload/' . $row["img"] . '" width="auto" height="70">';
    $tableHtml .= '<td>' . $row["prodname"] . '</td>';
    $tableHtml .= '<td>¥' . $row["price"] . '</td>';
    $tableHtml .= '<td>' . $row["shopname"] . '</td>';
    $tableHtml .= '<td>' . $row["address"] . '</td>';
    $tableHtml .= '<td>' . $row["remarks"] . '</td>';
    $tableHtml .= '<td>' . $row["indate"] . '</td>';
    $tableHtml .= '<td>' . $row["name"] . '</td>';


    if ($_SESSION["kanri_flg"] == "1") {
      $tableHtml .= '<td>';
      $tableHtml .= '<a class="btn btn-danger" href="delete.php?id=' . $row["id"] . '">';
      $tableHtml .= '[<i class="glyphicon glyphicon-remove"></i>削除]';
      $tableHtml .= '</a>';
      $tableHtml .= '</td>';
    }

    $tableHtml .= '</tr>';
  }
  $tableHtml .= '</table>';
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>おみやげリスト</title>
  <link rel="stylesheet" href="css/range.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/transitions.css"> <!-- 新しいCSSファイルを追加 -->
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body id="main">
  <!-- Head[Start] -->
  <?php include("menu.php"); ?> <!-- 上のメニューバーの表示-->
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <div>
    <h1>おみやげリスト</h1>
    <div>
      <input type="text" id="keyword">
      <button id="send">検索</button>
    </div>

    <h4>リスト登録</h4>
    <ul>
    <li><a href="index.php">インプット</a></li>
    <li>交際費記録アップロード</li>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="アップロード" name="submit">
  </ul>
    </form>


    <!-- <h4>データ登録</h4>
    <ul>
      <li><a href="index.php">直接インプット</a></li>
      <li>
        <form action="upload.php" method="post" enctype="multipart/form-data">
          <label for="fileToUpload">交際費使用記録アップロード</label>
          <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
          <button type="button" onclick="document.getElementById('fileToUpload').click();">ファイルを選択</button>
          <input type="submit" value="アップロード" name="submit" style="display: none;">
        </form>
      </li>
    </ul> -->
    
    <div class="container jumbotron" id="view"><?= $tableHtml ?></div>

  </div>
  <!-- Main[End] -->

  <!-- MAP[START] -->
  <h1>Map</h1>
  <!-- <div id="geocode">geocode:data</div> -->
  <div id="myMap" style="position:relative;width:100%;height:450px;"></div>
  <!-- MAP[END] -->

  <script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=Anlxkbqv4lpK0k9uhL-XOO2q2ShRpZX_AJJx1HBE5ul54xQSWk2uU0j6XF026Vtp' async defer></script>
  <script src="https://cdn.jsdelivr.net/gh/yamazakidaisuke/BmapQuery/js/BmapQuery.js"></script>
  <script>
    //****************************************************************************************
    // BingMaps&BmapQuery
    //****************************************************************************************
    //Init
    function GetMap() {
      //------------------------------------------------------------------------
      //1. Instance
      //------------------------------------------------------------------------
      const map = new Bmap("#myMap");

      //------------------------------------------------------------------------
      //2. Display Map
      //   startMap(lat, lon, "MapType", Zoom[1~20]);
      //   MapType:[load, aerial,canvasDark,canvasLight,birdseye,grayscale,streetside]
      //--------------------------------------------------
      map.startMap(47.6149, -122.1941, "load", 10);

      //----------------------------------------------------
      //3. Geocode(2 patterns)
      // getGeocode("searchQuery",callback);
      //----------------------------------------------------
      <?php
      // データベースから住所情報を取得
      $sql = "SELECT address FROM gs_an_table_fn";
      $result = $pdo->query($sql); // $pdoを使用してクエリを実行

      if ($result === false) {
        echo "Query execution failed: " . $pdo->errorInfo()[2]; // エラーメッセージを表示
      } else {
        if ($result->rowCount() > 0) {
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $address = $row["address"];
            echo 'map.getGeocode("' . $address . '", function(data){';
            echo 'const lat = data.latitude;';
            echo 'const lon = data.longitude;';
            echo 'map.pin(lat, lon, "#ff0000");';
            echo '});';
          }
        } else {
          echo 'No addresses found in the database.';
        }
      }
      ?>
      // //B.Get geocode of click location
      // map.onGeocode("click", function(data){
      //         console.log(data);                   //Get Geocode ObjectData
      //         const lat = data.location.latitude;  //Get latitude
      //         const lon = data.location.longitude; //Get longitude
      //         document.querySelector("#geocode").innerHTML=lat+','+lon;
      //     });
    }
  </script>


  </script>



  <!-- 以下にJavaScriptのコードを追加 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    //登録ボタンをクリック
    $("#send").on("click", function() {
      //axiosでAjax送信
      //Ajax（非同期通信）
      const params = new URLSearchParams();
      params.append('keyword', $("#keyword").val());

      //axiosでAjax送信
      axios.post('select2.php', params).then(function(response) {
        console.log(typeof response.data); //通信OK
        //>>>>通信でデータを受信したら処理をする場所<<<<
        document.querySelector("#view").innerHTML = response.data;
      }).catch(function(error) {
        console.log(error); //通信Error
      }).then(function() {
        console.log("Last"); //通信OK/Error後に処理を必ずさせたい場合
      });
    });
  </script>
</body>

</html>