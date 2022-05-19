<?php

function dbc()                                                  //データベースに接続する関数を作るぞ！
{
    $host = "localhost";                                        //MAMPのデータベースを作成したときに使った名前などを関数に入れておく
    $dbname = "file_db";
    $user = "root";                                             //とりあえず初期設定
    $pass = "root";

    $dns = "mysql:host=$host;dbname=$dbname;charset=utf8";      //PHPのPDOオブジェクトの第一引数に入れる「接続文字列」を変数に入れてます。

    try{
        $pdo = new PDO($dns, $user, $pass,                      //PHPのPDOオブジェクト（PHP Data Object）を作成。PDOを呼び出す。参考：https://www.youtube.com/watch?v=zz7KzILdBpU&list=PLCyDm9NTxdhIwBK3hsY_2GNg8BPgLMV1M&index=3
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        //エラーモード
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC    //デフォルトFETCHモード
        ]);
        return $pdo;                                            //関数dbc()を動かしたときにPDOを使えるようになる

    }catch(PDOException $e){                                    //例外が発生したときの処理。PDOExceptionを変数eに入れる
        exit($e->getMessage());
    }
}




/**
 * ファイルデータを保存する関数
 * @param string $filename ファイル名
 * @param string $save_path 保存先のパス
 * @param string $caption 投稿の説明
 * @return bool $result
*/

function fileSave($filename, $save_path, $caption)              //file_upload.phpで呼び出す関数fileSave()をここで書いてます
{
    $result = False;                                            //returnで返す値の初期値はFalseとする。

    $sql = "INSERT INTO file_table (file_name, file_path, description) VALUE(?, ?, ?)";  //データを登録するSQL構文。phpMyAdminで作ったテーブルfile_tableの３つのカラムに、VALUE（入れたい値）を入れる。
    
    try
    {
        $stmt = dbc()->prepare($sql);                       //SQLの準備
        $stmt->bindValue(1, $filename);                     //execute()を実行する前にbindValue()でVALUEの「?」に値を入れる
        $stmt->bindValue(2, $save_path);
        $stmt->bindValue(3, $caption);
        $result = $stmt->execute();                         //SQLを実行する。成功したらtrueが返ってくる。
        return $result;                 
    }
    catch(\Exception $e)                                    //例外処理をキャッチ。
    {
        echo $e->getMessage();
        return $result;
    }
}


/**
 * ファイルデータを取得する関数
 * @return array $fileData
*/

function getAllFile()
{
    $sql = "SELECT * FROM file_table";

    $fileData = dbc()->query($sql);

    return $fileData;
}

// エスケープ
function h($s){
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");                   //フォームからhtmlタグが投稿された場合、タグを文字列として認識して予期せぬ挙動を起こすことを回避するhtmlspecialchars()
}

?>
