<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続
function db_conn(){
  try {
    $db_name = "aya-17-ms_gs_db5";    //データベース名
    $db_id   = "aya-17-ms";      //アカウント名
    $db_pw   = "1287Tmam";      //パスワード：XAMPPはパスワード無しに修正してください。
    $db_host = "mysql57.aya-17-ms.sakura.ne.jp"; //DBホスト
    return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
  } catch (PDOException $e) {
    exit('DB Connection Error:'.$e->getMessage());
  }
}

//SQLエラー
function sql_error($stmt){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}

//リダイレクト
function redirect($file_name){
    header("Location: ".$file_name);
    exit();
}

// //1秒後にリダイレクト
// function redirect1sec($file_name){
//   header("refresh:1;".$file_name);
//   exit();
// }

//SessionCheck
function sschk(){
  if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
    exit("Login Error");
  }else{
    session_regenerate_id(true);
    $_SESSION["chk_ssid"] = session_id();
  }
}

//fileUpload("送信名","アップロード先フォルダ");
function fileUpload($fname,$path){
    if (isset($_FILES[$fname] ) && $_FILES[$fname]["error"] ==0 ) {
        //ファイル名取得
        $file_name = $_FILES[$fname]["name"];
        //一時保存場所取得
        $tmp_path  = $_FILES[$fname]["tmp_name"];
        //拡張子取得
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // 許可する拡張子を指定
        $allowed_extensions = array("png", "jpg", "jpeg", "gif"); // ここに許可する拡張子を指定
        
        // 拡張子が許可されているかチェック
        if (in_array(strtolower($extension), $allowed_extensions)) { // 拡張子を小文字で比較
        //ユニークファイル名作成  md5とはハッシュ値のこと
        $file_name = date("YmdHis").md5(session_id()) . "." . $extension;
        // FileUpload [--Start--]
        $file_dir_path = $path."/".$file_name;
        if (is_uploaded_file($tmp_path)) {
            if (move_uploaded_file($tmp_path, $file_dir_path )) {
              //changemodeのこと
                chmod($file_dir_path, 0644 );
                return $file_name; //成功時：ファイル名を返す
            } else {
                return "ファイル移動に失敗"; //失敗時：ファイル移動に失敗
            }
        }
     }else{
         return "ファイル取得エラー"; //失敗時：ファイル取得エラー
     }
}
}