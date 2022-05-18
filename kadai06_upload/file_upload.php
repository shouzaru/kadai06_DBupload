<?php

require_once "./dbc.php";                                               //dbc.phpを呼び出す！（ファイルが既に読み込まれている場合は再読み込みしない）

// ファイル関連の取得
$file = $_FILES['img'];                                                 //imgはフォームで指定した名前。これをPHPの機能である$_FILESで受け取る。
$filename = basename($file['name']);                                    //PHPのbasename()関数は絶対パスの文字列からファイル名だけ抜き出す
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
// $upload_dir = '/Applications/MAMP/htdocs/kadai06_upload/images/';     //これはMac上のパス
$upload_dir = 'images/';                                                //MAMP上のパスを指定する。MAMPの場合トップのディレクトリはkadai06_upload/

$save_filename = date('YmdHis').$filename;
$err_msgs = array();                                                    //エラーメッセージを格納する配列を宣言（エラーメッセージが0だった時だけファイルアップロードしたいので）
$save_path = $upload_dir.$save_filename;                                //保存先ディレクトリ名+日付時間+ファイル名


// キャプションの取得
$caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_SPECIAL_CHARS); // filter_input()はphpのメソッドで「指定した名前の変数を外部から受け取り、オプションでそれをフィルタリングする」

// キャプションのバリデーション
if(empty($caption)){                                                   // もしcaptionが空だったら、
    array_push($err_msgs, 'キャプションを入力してください。');              //配列$err_msgに追加
}
// もし140文字以上だったら、、
if(strlen($caption) > 140){
    array_push($err_msgs, 'キャプションは140文字以内で入力してください。');  //配列$err_msgに追加
}

// ファイルのバリデーション
// ファイルサイズが1MB以上だったら
if($filesize > 1048576 || $filesize == 2){                          //upload_form.phpで指定したMAX_FILE_SIZEは、それを超えていると[2]が返ってきます
    array_push($err_msgs, 'ファイルサイズは1MB未満にしてください。');     //配列$err_msgに追加
}

// 拡張子は画像形式か？
$allow_ext = array('jpg', 'jpeg', 'png', 'pdf');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);                //拡張子を取得する

if(!in_array(strtolower($file_ext), $allow_ext)){                   //in_array()は配列の中にあるかどうかを判定する。strtolowerは大文字を小文字にするPHPの関数
    array_push($err_msgs, '画像ファイルを添付してください');             //配列$err_msgに追加
}

if(count($err_msgs) === 0){                                         //count()で配列$err_msgの中の数を取得して、何も入っていない（エラーメッセージが無い）なら

    if(is_uploaded_file($tmp_path)){                                //一時保存ディレクトリにファイルがあったら

        if(move_uploaded_file($tmp_path, $save_path)){              //PHPのmove_uploaded_file()関数でtmpディレクトリから保存用ディレクトリにデータを移動する
            echo $filename . 'を' . $upload_dir . 'にアップしました。'; 

            //さらにファイルをデータベースにも保存する。保存するのはファイル名、パス、キャプション。
            $result = fileSave($filename, $save_path, $caption);    // DBに保存する関数fileSave()を宣言。引数は（ファイル名、ファイルパス、キャプション）。長くなりそうなのでこの関数はdbc.phpの中で定義した。

            if($result){                                            //$resultに代入した関数fileSave()の戻り値がtrueだったら
                echo 'データベースに保存しました！';
            }else{
                echo 'データベースへの保存に失敗しました';
            }
        }
        else{                                                       //move_uploaded_file()が失敗するとFalseが返ってくる。つまりファイルの移動が失敗した場合。
            echo 'ファイルが保存できませんでした。';
            }
    }else{                                                          //一時ディレクトリにファイルがなければ
        echo 'ファイルが選択されていません。';
        echo '<br>';
    }
}else{                                                              //何らかのエラーメッセージがあったら
    foreach($err_msgs as $msg){                                     //配列$err_msgから一つづつ取り出して
        echo $msg;
        echo '<br>';
    }
}

?>

<a href="./upload_form.php">戻る</a>