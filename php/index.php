<?php
    echo get_include_path();

    error_log("test", 3, "D:/wamp/logs/test");

    $url = "";
    $header = "";
    $body = "";
    $res = "";
    $message = array(
        'url' => $url,
        'method' => 'post',
        'header' => $header,
        'body' => $body,
        'response' => $res,
    );
    $message = json_encode($message);
    $date = date("Ymd");
    //error_log($message, 3, "/var/log/httpd/coolpen_log-{$date}");
    error_log("[" . date("Y-m-d H:i:s") . "] " . $message . "\r", 3, "D:/wamp/logs/coolpen_log-{$date}");

    $url = "{USER}.yongche.org";
    $url = "testing.yongche.org";
    echo "test" . strpos($url, "{USER}");
    echo "test" . gettype(strpos($url, "{USER}"));
    if(strpos($url, "{USER}") > 0 || strpos($url, "{USER}") === 0) {
    	echo "please change the config";
    }