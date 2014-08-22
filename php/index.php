<?php
    info(get_include_path());

    date_default_timezone_set("Asia/Shanghai");
    //error_log("test", 3, "D:/wamp/logs/test");

    echo hellotom_test("ttttttttttttttttttttttttttt");

    yc_geo_get_city_list(YC_GEO_FLAG_WITH_ALL|YC_GEO_COORD_TYPE_MARS);

    info($_SERVER['SERVER_NAME']);

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

    info(date("Y-m-d H:i:s"));
    info(time());
    info(yc_geo_get_timezone_by_city("bj"));
    $dateTimeZone = new DateTimeZone('America/Denver');
    $dateTime = new DateTime();
    $dateTime->setTimestamp(time());
    $dateTime->setTimezone($dateTimeZone);
    info($dateTime->format('Y-m-d H:i:s'));

    $ids = explode(",", "1,2,3");
    println($ids);
    list($carTypeIds, $carTypeName) = array($ids, '');
    println($carTypeIds);

    function info($content) {
        echo $content . "<br />";
    }

    function println($content) {
        print_r($content);
        echo "<br />";
    }

    $versionKey = "";
    $weidaoVersionKey = "";
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

    info(strlen("123456"));
    info(strlen(123456));

    $max_time = 9999999999;
    $time = 1322551341;//10

    info(PHP_INT_MAX);//2147483647 9223372036854775807
    info(is_numeric((int)"1322551341"));
    info(is_numeric("a") . "t");

    $startTime = -1 ? : $now;
    info($startTime);

    require('Model/UserFavor.php');
    $userFavor = new UserFavor();
    println($userFavor->saveUserFavorList(1, array()));

    $request = array();
    $request["name"] = "";
    info(isset($request["name"]));
    $num = (int) $request["name"];
    info($num);

    function indexAutoload($clazz) {
        $file = str_replace('_', '/', $clazz);

        if(is_file("/usr/share/pear/$file.php"))
            require "/usr/share/pear/$file.php";
    }

    spl_autoload_register('indexAutoload');

    $config = new Zend_Config(array(
        'proxy' => array(
            'proxy' => '10.1.5.13:8087',
            'proxyAuth' => ''
        ),
        'oversea_server' => array(
            'map' => 'http://54.254.199.29'
        ),
        'map_baidu' => array(
            'key' => 'DEd51bf09fcded27d8705981745b7351',
        ),
        'map_google' => array(
            'key' => 'AIzaSyBF5oBKe7CNQUddA4bAokOWgSYNq4NmB4I',
        ),
        'map_amap' => array(
            'key' => '17f352fa1e15fd82d377ea0ea939131c',
        ),
        'oauth' => array(
            0x10 => "sina",
            'sina' => array(
                'consumerKey' => '2847323495',
                'consumerSecret' => 'a27210f2a1bafaf7c496cff086606ce1',
                'siteUrl' => 'https://api.weibo.com/oauth2/',
                'apiUrl' => 'https://api.weibo.com/2/',
                //'callbackUrl' => '',
                'source_flag' => 0x10,
                'default_out_user_id' => 1830346007,  // 默认微博账户ID(用于抓取数据，发布公告微博)
            )
        ),
    ));
    Zend_Registry::set('config', $config);

    info("test");
    $map = new YCL_Map();
    //println($map->nearbysearch(-33.8670522, 151.1957362, 500, 'l'));
    info("test");

    println($map->getGoogleDistance(array(
        array('lng' => -73.994529,'lat' => 40.735243),
        array('lng' => -74.009735,'lat' => 40.705697)
    )));

    println($map->getDistance(array(
        array('lng' => -73.994529,'lat' => 40.735243),
        array('lng' => -74.009735,'lat' => 40.705697)
    )));

    println($map->getDistance(array(
        array('lng' => 116.481028,'lat' => 39.989643),
        array('lng' => 114.465302,'lat' => 40.004717)
    )));

    println($map->getDistanceExtra(array(
        array('lng' => 116.481028,'lat' => 39.989643),
        array('lng' => 114.465302,'lat' => 40.004717)
    ), '', ''));

    println($map->getAmapDistance(array(
        array('lng' => 116.481028,'lat' => 39.989643),
        array('lng' => 114.465302,'lat' => 40.004717)
    )));

    println($map->getGoogleDistance(array(
        array('lng' => 0,'lat' => 90),
        array('lng' => 114.465302,'lat' => 40.004717)
    )));

    //Array ( [distance] => 5049 [duration] => 760 [driver] => 760 [taxi_amount] => 0 )

    $timezone_abbreviations = DateTimeZone::listAbbreviations();
    //println($timezone_abbreviations);

    date_default_timezone_set('America/New_York');
    info(date_default_timezone_get() . ' => ' . date('e') . ' => ' . date('T'));

    //http://en.m.wikipedia.org/wiki/List_of_time_zone_abbreviations
    //http://www.php.net/manual/fr/function.date-default-timezone-set.php
    $dateTimeZone = new DateTimeZone("America/St_Johns");//America/New_York America/St_Johns
    //$dateTimeZone = new DateTimeZone("Asia/Katmandu");//Asia/Rangoon Asia/Shanghai Asia/Katmandu
    $dateTimeZ = new DateTime("now", $dateTimeZone);
    $timeOffset = floor($dateTimeZone->getOffset($dateTimeZ) / 3600);
    info($timeOffset);
    $timeOffset = round($dateTimeZone->getOffset($dateTimeZ) % 3600) / 60;
    info($timeOffset);

    $num = -10;
    info(-$num);
    /**
     * -25200|International Date Line (West) GMT-12|
    -21600|Midway Island, Samoa GMT-11|
    -18000|Hawaii, Honolulu GMT-10|
    -14400|Alaska GMT-9|
    -10800|Pacific Standard Time, US, Canada GMT-8|
    -7200|British Columbia N.E., Santa Fe, Mountain Time GMT-7|
    -3600|Central America, Chicago, Guatamala, Mexico City GMT-6|
    0|US, Canada, Bogota, Boston, New York GMT-5|
    +3600|Canada, Santiago, Atlantic Standard Time GMT-4|
    +7200|Brazilia, Buenos Aires, Georgetown, Greenland GMT-3|
    +10800|Mid-Atlantic GMT-2|
    +14400|Azores, Cape Verde Is., Western Africa Time GMT-1|
    +18000|London, Iceland, Ireland, Morocco, Portugal GMT|
    +21600|Amsterdam, Berlin, Bern, Madrid, Paris, Rome, GMT+1|
    +25200|Athens, Cairo, Cape Town, Finland, Greece, Israel GMT+2|
    +28800|Ankara, Aden, Baghdad, Beruit, Kuwait, Moscow GMT+3|
    +32400|Abu Dhabi, Baku, Kabul, Tehran, Tbilisi, Volgograd GMT+4|
    +36000|Calcutta, Colombo, Islamabad, Madras, New Dehli GMT+5|
    +39600|Almaty, Dhakar, Kathmandu, Colombo, Sri Lanka GMT+6|
    +43200|Bangkok, Hanoi, Jakarta, Phnom Penh, Australia GMT+7|
    +46800|Taipei, Beijing, Hong Kong, Singapore, GMT+8|
    +50400|Seoul, Tokyo, Central Australia GMT+9|
    +54000|Brisbane, Canberra, Guam, Melbourne, Sydney, GMT+10|
    +57600|Magadan, New Caledonia, Solomon Is. GMT+11|
    +61200|Auckland, Fiji, Kamchatka, Marshall, Wellington, GMT+12|
     */

    class A {
        public $property = 'property 1';
    }
    $a = new A;
    $c = clone $a;
    $a->property = 'change2';
    var_dump($a, $c);

    $b = $a;
    $a->property = 'change';
    var_dump($a, $b);

    $a = array('ni', 'shi', 'shui');
    $b = $a;
    $a[0] = 'hh';
    var_dump($a, $b);

    class A1 {
        public $property1 = 'property A 1';
        public $property2;

        public function __construct() {
            $this->property2 = new B();
        }
    }

    class B {
        public $property1 = 'property B 1';
    }

    $a = new A1();
    $b = clone $a;
    $a->property1 = 'new property1';
    $a->property2->property1 = 'new property2';
    var_dump($a, $b);

    info(substr_count("-7-9", "-"));

    //-15d -7-9,-17-19 +9
    $limit_time_desc = array();
    $webDesc = array();
    $sourceDesc = "";
    $limit_time_set = "+9";//格式: 提前15天: -15d; 限制高峰时间: -7-9,-17-19; 只限9点用车: +9
    function _get_limit_time_set($coupon, &$limit_time_desc, &$webDesc) {
        if($coupon['limit_time_set']) {
            $limit_time_set = $coupon['limit_time_set'];
            $sourceDesc = '仅适用于';

            $first = $limit_time_set[0];
            $isMinus = $first == '-';
            $isAdd = !$isMinus;

            if(stripos($limit_time_set, "d") > 0) {
                $days = str_replace("d", "", $limit_time_set);
                if($days > 0) {
                    $sourceDesc .= "延后" . $days . "天、";
                } else {
                    $sourceDesc .= "提前" . -$days . "天、";
                }
            } else if(stripos($limit_time_set, "w") > 0) { // -67w  周六日不能用车, +135w, 135w 只能周1,3,5使用
                $weeks = str_replace("w", "", $limit_time_set);
                $week = "";
                for($i = 0 ; $i < strlen($weeks) ; $i++) {
                    $week .= $weeks[$i] . ",";
                }
                $week = substr($week, 0, strlen($week) - 1);
                if($isMinus) {
                    $sourceDesc = "周" . $week . "不能用车、";
                } else {
                    $sourceDesc = "只能周" . $week . "使用、";
                }
            } elseif(stripos($limit_time_set, ",") > 0) {
                $limit_time_array = explode(",", $limit_time_set);
                foreach($limit_time_array as $limit_time_key => $limit_time_value) {
                    $time_set = explode("-", substr($limit_time_value, 1));
                    $sourceDesc .= "非" . $time_set[0] . ":00-" . $time_set[1] . ":00、";
                }
            } elseif(substr_count($limit_time_set, "-") == 2) {
                $time_set = explode("-", substr($limit_time_set, 1));
                $sourceDesc .= "非" . $time_set[0] . ":00-" . $time_set[1] . ":00、";
            } else {
                $limit_time_set = substr($limit_time_set, 1);
                $sourceDesc .= $limit_time_set . ":00、";
            }
            if($sourceDesc) {
                //去掉最后一个叹号
                $limit_time_desc[] = rtrim($sourceDesc, '、');
                $webDesc['适应时间'] = rtrim($sourceDesc, '、');
            }
        }
    }

    $desc = "abcd";
    info(strlen($desc));
    for($i = 0 ; $i < strlen($desc) ; $i++) {
        info($desc[$i]);
    }

    $code = null;
    info($code ?: "not null");
    $code = "";
    info($code ?: "not null");

    mb_internal_encoding("UTF-8");
    $driver_name = "一二三";
    info(mb_strlen($driver_name));
    info(mb_substr($driver_name, 0, 1));

    $driver_name = "a一二三四";
    info(mb_strlen($driver_name));
    info(mb_substr($driver_name, 0, 1));

    println(YCL_Social_Weibo::getShortUrl("http://www.yongche.com"));
    println(YCL_Social_Weibo::getShortUrl(array("http://www.yongche.com","http://3g.yongche.com")));

    $filename = "g1/M00/00/00/CgAL61PrXUSIZzscAAAUGH_5CY0AAAAAQAAAAAAABQw6780.sh";
    //println(YCL_File_FastDFS::getFile("g1/M00/00/00/CgAL61PrXUSIZzscAAAUGH_5CY0AAAAAQAAAAAAABQw6780.sh"));

    $id = substr($filename, strrpos($filename, "/") + 1);
    $name = substr($id, 0, strrpos($id, ".") > 0 ? strrpos($id, ".") : strlen($id));
    $suffix = strrpos($id, ".") > 0 ? substr($id, strrpos($id, ".") + 1) : "txt";
    info($name . " " . $suffix);

    $id = "g1_M00_00_00_CgAL61P2vi2ILjIFAABCoHONyA8AAAAAQACkPwAAEK4729.png";

    info(_getFastDFSId($id));
    function _getFastDFSId($id) {
        $ids = explode("_", $id);
        $fastdfs_id = "";
        for($i = 0 ; $i < 4 ; $i++) {
            $fastdfs_id .= $ids[$i] . "/";
        }

        for($i = 0 ; $i < 4 ; $i++) {
            array_shift($ids);
        }

        $fastdfs_id .= implode("_", $ids);
        return $fastdfs_id;
    }