<?php

define( 'FILENAME', './message.txt');

date_default_timezone_set('Asia/Tokyo');

$current_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$clean = array();
$pdo = null;
$stmt = null;
$res = null;
$option = null;

try {
   $option = array(
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
   );

   $pdo = new PDO('mysql:charset=UTF8;dbname=board;host=localhost', 'root', 'password');
} catch(PDOException $e) {
  $error_message[] = $e->getMessage();
}

if( !empty($_POST['btn_submit']) ) {
  if( empty($_POST['view_name']) ) {
  }else{
    $clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES, 'UTF-8');
    $clean['view_name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['view_name']);
  }

  if( empty($_POST['message']) ) {
  }else{
    $clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES, 'UTF-8');
    $clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
  }

  if( $file_handle = fopen( FILENAME, "a") ) {
    $current_date = date("Y-m-d H:i:s");
    $data = "'".$clean['view_name']."','".$_POST['message']."','".$current_date."'\n";
    fwrite( $file_handle, $data);

    fclose( $file_handle);
  }

  $current_date = date("Y-m-d H:i:s");
  
}





if( $file_handle = fopen( FILENAME,'r') ) {
  while( $data = fgets($file_handle) ){
    $split_data = preg_split( '/\'/', $data);
    $message = array(
                  'view_name' => $split_data[1],
                  'message' => $split_data[3],
                  'post_date' => $split_data[5]
    );
    array_unshift( $message_array, $message);

  }
  fclose( $file_handle);
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <title></title>
  </head>
  <body>
    <header>
      <a href="index.php"><h1 class="header-logo">10chan</h1></a>
    </header>

    <main>
      <?php if( !empty($message_array) ): ?>
      <?php foreach( $message_array as $value ): ?>
      <article class="message">
        <div class="info">
          <h3><?php echo $value['view_name']; ?></h3>
          <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
        </div>
        <p><?php echo $value['message']; ?></p>
      </article>
      <?php endforeach; ?>
      <?php endif; ?>
    </main>

    <div class="form">
      <form method="post">
       <div class="inForm">
        <div>
          <label for="view_name" class="name-text">投稿名</label>
          <input id="view_name" type="text" name="view_name" value="" class="name">
        </div>
        <div>
          <label for="message">本文</label>
          <textarea id="message" name="message" class="main-txt"></textarea>
        </div>
        <input type="submit" name="btn_submit" value="書き込む" class="submit-bottun">
       </div>
      </form>
    </div>
  </body>
</html>
