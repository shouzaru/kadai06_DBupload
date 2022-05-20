<?php

require_once "./dbc.php";    
$files = getAllFile();
// foreach($files as $file){
//   print_r($file);
// }


?>
<!-- ①フォームの説明 -->
<!-- ②$_FILEの確認 -->
<!-- ③バリデーション -->

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHPでアップロードフォーム</title>
  </head>
  <style>
    body {
      margin:50px;
    }
    textarea {
      width: 98%;
      height: 60px;
    }
    .file-up {
      margin-bottom: 10px;
    }
    .submit {
      text-align: right;
    }
    .btn {
      display: inline-block;
      border-radius: 3px;
      font-size: 18px;
      background: #67c5ff;
      border: 2px solid #67c5ff;
      padding: 5px 10px;
      color: #fff;
      cursor: pointer;
    }
    img{
      height: 300px;
      display:block;
    }
    .submit{
      margin:20px auto;
    }
  </style>
  <body>
    <!-- file_upload.phpに対してPOSTする -->
    <form enctype="multipart/form-data" action="./file_upload.php" method="POST">   <!-- formのenctypeをmultipart指定しファイルを含めて複数のデータを送る --> 
      <div class="file-up">
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />                <!-- type="hidden"でファイルの最大サイズを指定している -->
        <input name="img" type="file" accept="image/*" />                           <!-- imgを$_FILESで受け取るようにする。acceptで画像の拡張子だけを選択するようにしている -->
      </div>
      <div>
        <textarea
          name="caption"
          placeholder="キャプション（140文字以下）"
          id="caption"
        ></textarea>
      </div>
      <div class="submit">
        <input type="submit" value="送信" class="btn" />
      </div>
    </form>
    <div>
      <hr>
      <h2>アップロード済みの画像ファイル</h2>
      <?php foreach($files as $file): ?>
        <img src="<?php echo "{$file['file_path']}";?>" alt="">
        <p><?php echo h("{$file['description']}");?></p>                            <!--関数h()はdbc.phpで作成した。 -->
      <?php endforeach; ?>
    </div>


  </body>
</html>
