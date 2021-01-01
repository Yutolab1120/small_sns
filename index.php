<?php

// メッセージを保存するファイルのパス設定
define('FILENAME', './message.txt');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();


if (!empty($_POST['btn_submit'])) {

    // 表示名の入力チェック
    if (empty($_POST['view_name'])) {
        $error_message[] = '表示名を入力してください。';
    } else {
        $clean['view_name'] = htmlspecialchars($_POST['view_name'], ENT_QUOTES);
    }

    // メッセージの入力チェック
    if (empty($_POST['message'])) {
        $error_message[] = 'メッセージを入力してください。';
    } else {
        $clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
        $clean['message'] = preg_replace('/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
    }

    if (empty($error_message)) {

        if ($file_handle = fopen(FILENAME, "a")) {

            // 書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");

            // 書き込むデータを作成
            $data = "'" . $clean['view_name'] . "','" . $clean['message'] . "','" . $now_date . "'\n";

            // 書き込み
            fwrite($file_handle, $data);

            // ファイルを閉じる
            fclose($file_handle);

            $success_message = 'メッセージを書き込みました。';
        }
    }
}

if ($file_handle = fopen(FILENAME, 'r')) {
    while ($data = fgets($file_handle)) {

        $split_data = preg_split('/\'/', $data);

        $message = array(
            'view_name' => $split_data[1],
            'message' => $split_data[3],
            'post_date' => $split_data[5]
        );
        array_unshift($message_array, $message);
    }

    // ファイルを閉じる
    fclose($file_handle);
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title>mixel +</title>
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="bagelee">
    <link rel="apple-touch-icon" href="images/icon.jpg" sizes="192x192">


    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@Yutolab1120" />
    <meta property="og:url" content="http://lisur.hacklife.work" />
    <meta property="og:title" content="mixel" />
    <meta property="og:description" content="mixelでもっとシンプルに交じろう。" />
    <meta property="og:image" content="https://mixel.glitch.me/dist/images/ogp.png" />


</head>

<body>
    <!-- Just an image -->
    <nav class="sticky-top navbar navbar-light bg-light-1">
        <div id="wrap">
            <a class="navbar-brand" href="">
                <center>
                    <pp style="font-weight: 400; font-size:30px;">mixel +</pp>
                </center>
            </a>
        </div>
    </nav>

    <br class="one_one">
    <div id="wrap">
        <!-- Button trigger modal -->
        <button class="b-b-b" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
            最近の出来事を共有しよう。
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">ストリームに共有</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <section>
                            <div class='foo foo--inside'>
                                <article>
                                    <form method="post">
                                        <div>
                                            <input class="input" id="view_name" type="text" name="view_name" value="" placeholder="&#xf007;&nbsp;&nbsp;名前を入力">
                                            <div class="underline"></div><br>
                                            <textarea class="input" id="message" name="message" placeholder="&#xf27a;&nbsp;&nbsp;出来事を共有"></textarea>
                                            <div class="underline"></div><br>
                                        </div><br>
                                        <input class="button_sousin" type="submit" name="btn_submit" value="&#xf1d8;&nbsp;&nbsp;投稿する">
                                    </form>
                                </article>

                            </div>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                    </div>
                </div>
            </div>
        </div>

        <section>
            <div class='foo foo--inside'><br>


                <?php if (!empty($success_message)) : ?>
                    <p class="success_message"><?php echo $success_message; ?></p>
                <?php endif; ?>
                <?php if (!empty($error_message)) : ?>
                    <ul class="error_message">
                        <?php foreach ($error_message as $value) : ?>
                            <li>・<?php echo $value; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>


                <section>
                    <!-- ここにメッセージ -->
                    <?php if (!empty($message_array)) { ?>
                        <?php foreach ($message_array as $value) { ?>
                            <article type="funnys">
                                <div class="info">
                                    <h2><?php echo $value['view_name']; ?></h2>
                                    <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
                                </div>
                                <p><?php echo $value['message']; ?></p>
                            </article>
                        <?php } ?>
                    <?php } ?>
                </section><!-- ここまで -->

            </div>
        </section>
        </section>


    </div>
    <button type="button" class="btn fixed_btn btn-primary rounded-circle p-0 btn-lg" data-toggle="modal" data-target="#exampleModalLong" style="border: none;background-color: #DB4437; width:4rem;height:4rem;">＋</button>

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
    <script>
        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </div>
</body>

</html>