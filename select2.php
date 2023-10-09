<?php
ini_set('display_errors', '1');
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();
$keyword = $_POST['keyword'];

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_an_table_fn WHERE genre LIKE :keyword OR
prodname LIKE :keyword OR
price LIKE :keyword OR
img LIKE :keyword OR
shopname LIKE :keyword OR
address LIKE :keyword OR
remarks LIKE :keyword OR
indate LIKE :keyword OR
name LIKE :keyword");
$stmt->bindValue(':keyword', '%'.$keyword.'%', PDO::PARAM_STR);
$status = $stmt->execute();

//３．データ表示
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

echo $tableHtml;
?>