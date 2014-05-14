<?php
    info(get_include_path());

    date_default_timezone_set("Asia/Shanghai");
    //error_log("test", 3, "D:/wamp/logs/test");

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
    //error_log("[" . date("Y-m-d H:i:s") . "] " . $message . "\r", 3, "D:/wamp/logs/coolpen_log-{$date}");

    $url = "{USER}.yongche.org";
    $url = "testing.yongche.org";
    info("test" . strpos($url, "{USER}"));
    info("test" . gettype(strpos($url, "{USER}")));
    if(strpos($url, "{USER}") > 0 || strpos($url, "{USER}") === 0) {
    	info("please change the config");
    }

    test_func(array('limit'=>20));
    function test_func($limit_arg = array('limit'=>10,'interval'=>10)) {
        print_r($limit_arg);
    }

    info(floor(5000001 / 1000000));
    info(floor(5009999 / 1000000));

    function info($content) {
        echo $content . "<br />";
    }

    function println($content) {
        print_r($content);
        echo "<br />";
    }

    $updateConfig = array(
        'increase_config_version' => array(
            'message' => '增加版本号成功',
            'version_key' => $versionKey,
        ),
        'update_android' => array(
            'key_list' => array(
                'ANDROID_UPGRADE_TEXT',
                'ANDROID_VERSION',
                'ANDROID_MIN_STABLE_VERSION'
            ),
            'message' => '修改Android配置成功',
            'version_key' => $versionKey,
        ),
        'update_android_version' => array(
            'message' => '增加Android版本号成功',
            'version_key' => $versionKey,
        ),
        'update_ios' => array(
            'message' => '修改iOS配置成功',
            'key_list' => array(
                'IOS_UPGRADE_TEXT',
                'IOS_VERSION',
                'IOS_MIN_STABLE_VERSION',
                'COVER_VERSION',
                'COVER_URL',
                'COVER_URL_1136',
            ),
            'version_key' => $versionKey,
        ),
        'is_foreign_bank_active' => array(
            'message' => '修改外卡支持状态成功',
            'key_list' => array(
                'FOREIGN_BANK_ACTIVE'
            ),
            'version_key' => array($versionKey, $weidaoVersionKey),
        ),
        'is_globebill_foreign_bank_active' => array(
            'message' => '修改钱宝外卡支持状态成功',
            'key_list' => array(
                'GLOBEBILL_FOREIGN_BANK_ACTIVE'
            ),
            'version_key' => array($versionKey, $weidaoVersionKey),
        ),
        'increase_weidao_config_version' => array(
            'message' => '增加易到用车高级版版本成功',
            'key_list' => array(),
            'version_key' => $weidaoVersionKey,
        ),
        'update_weidao_image_version' => array(
            'message' => '增加易到用车高级版图片版本成功',
            'key_list' => array(
                'WEIDAO_IMAGE_VERSION',
            ),
            'version_key' => $weidaoVersionKey,
        ),
        'update_weidao_android_config' => array(
            'message' => '修改易到用车高级版Android配置成功',
            'key_list' => array(
                'WEIDAO_ANDROID_VERSION',
                'WEIDAO_ANDROID_STABLE_VERSION',
                'WEIDAO_ANDROID_UPGRADE_TEXT',
            ),
            'version_key' => $weidaoVersionKey,
        ),
        'update_weidao_cover_url' => array(
            'message' => '更新易到用车高级版COVER成功',
            'key_list' => array(
                'WEIDAO_COVER_URL',
                'WEIDAO_COVER_URL_1136'
            )
        ),
        'update_weidao_ios_config' => array(
            'message' => '修改易到用车高级版iOS配置成功',
            'key_list' => array(
                'WEIDAO_IOS_VERSION',
                'WEIDAO_IOS_STABLE_VERSION',
                'WEIDAO_IOS_UPGRADE_TEXT',
            ),
            'version_key' => $weidaoVersionKey,
        ),
    );

    $toIncreaseVersionKeyArr = array();
    foreach($updateConfig as $submit => $config) {
        info($submit);
    }