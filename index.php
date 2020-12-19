<?php

// データベース系(分割する場合各一つずつコード必要)
define( 'FILENAME', './message.txt');
date_default_timezone_set('Asia/Tokyo');

$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();



// メッセージ受信コード
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



// メッセージ送信コード
if( !empty($_POST['btn_submit']) ) {
	

	if( empty($_POST['view_name']) ) {
		$error_message[] = '「表示名」を入力してください。';
	} else {
		$clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES);
	}
	

	if( empty($_POST['message']) ) {
		$error_message[] = '本文を入力してください。';
	} else {
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
		$clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
	}

	if( empty($error_message) ) {

		if( $file_handle = fopen( FILENAME, "a") ) {
	
		    
			$now_date = date("Y-m-d H:i:s");
		
		
			$data = "'".$clean['view_name']."','".$clean['message']."','".$now_date."'\n";
		
		
			fwrite( $file_handle, $data);
		
			
			fclose( $file_handle);
	
			$success_message = '投稿しました。';
        }

        }
	}
	



?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title>LISUR TIMELINE</title>
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <link rel="manifest" href="manifest.json">
	<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="bagelee">
    <link rel="apple-touch-icon" href="images/icon.jpg" sizes="192x192">

	
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@Yutolab1120" />
    <meta property="og:url" content="http://lisur.hacklife.work" />
    <meta property="og:title" content="LISUR" />
    <meta property="og:description" content="LISURでもっとシンプルに交じろう。"/>
    <meta property="og:image" content="http://lisur.hacklife.work/dist/images/ogp.png" />


</head>

<body>
<!-- Just an image -->
<nav class="sticky-top navbar navbar-light bg-light-1">
  <a class="navbar-brand" href="">
    <img src="dist/images/logo.png" width="110" height="55" alt="">
  </a>
</nav>
<br>
<div id="wrap">
                <section>
                    <div class='foo foo--inside'>

                        <?php if( !empty($success_message) ): ?>
                        <p class="success_message"><?php echo $success_message; ?></p>
                        <?php endif; ?>
                        <?php if( !empty($error_message) ): ?>
                        <ul class="error_message">
                            <?php foreach( $error_message as $value ): ?>
                            <li>・<?php echo $value; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                  
                        <form method="post">
                            <div>
                                <input id="view_name" type="text" name="view_name" value="" placeholder="表示させたい名前を入力" value="表示させたいテキストを記述">

                                <textarea id="message" name="message" placeholder="最近どうしてる？"></textarea>
                            </div>
                            <input class="button_sousin" type="submit" name="btn_submit" value="投稿" >
                        </form>


                    </div>
                </section>

                <section>
                    <div class='foo foo--inside'>

                       


                        <section>
                            <?php if( !empty($message_array) ){ ?>
                            <?php foreach( $message_array as $value ){ ?>
                            <article>
                                <div class="info">
                                    <h2><?php echo $value['view_name']; ?></h2>
                                    <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
                                </div>
                                <p><?php echo $value['message']; ?></p>
                            </article>
                            <?php } ?>
                            <?php } ?>
                        </section>

                    </div>
                </section>
            </section>
</div>


    <script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript"></script>
<script>
    if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service_worker.js').then(function(registration) {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }).catch(function(err) {
        console.log('ServiceWorker registration failed: ', err);
    });
    }
    </script>
</div>
</body>

</html>
