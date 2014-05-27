<?php
class UserFavor {
    const FLAG_FM = 1;
    const FLAG_SLOW = 2;
    const FLAG_CHAT = 4;
    const FLAG_AIR_CONDITION = 8;
    const FLAG_FRONT_SEAT = 0x10;
    const FLAG_CAR_TYPE_ID = 0x20;
    const FLAG_AROMATHERAPY = 0x40;

    protected static $_favorList = array(
        self::FLAG_FM => 'fm',
        self::FLAG_SLOW => 'slow',
        self::FLAG_CHAT => 'chat',
        self::FLAG_FRONT_SEAT => 'front_seat',
        self::FLAG_AIR_CONDITION => 'air_condition',
        self::FLAG_CAR_TYPE_ID => 'car_type_id',
        self::FLAG_AROMATHERAPY => 'aromatherapy',
    );


    public static function getFavorList() {
        return self::$_favorList;
    }

    /**
     * 保存用户的个性化设置
     *
     * @param int   $userId
     * @param array $info
     *     fm:  是否听音乐
     *     slow:  是否慢慢开
     *     chat:  是否可以聊天
     *     front_seat: 是否坐前面
     *     air_condition: 是否打开空调
     *     car_type_id: 易出行的车新ID, 可能是多个，逗号分割即可
     *
     * @return array
     */
    public function saveUserFavorList($userId, $info) {
        $list = array_flip(self::$_favorList);
        return $list;
    }

    public function getUserFavorList($userId) {
        $list = self::$_favorList;
        $ret = array_combine(array_values($list), array_fill(0, count($list), 0));
        return $ret;
    }
}