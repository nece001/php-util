<?php

namespace Nece\Util;

/**
 * 中国相关工具方法
 *
 * @Author nece001@163.com
 * @DateTime 2023-07-02
 */
class ChinaUtil
{
    /**
     * 遮蔽手机号
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $mobile
    
     * @return string
     */
    public static function maskMobile($mobile)
    {
        return StringUtil::mask($mobile, 3, 4);
    }

    /**
     * 遮蔽身份证号
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $id_card
     *
     * @return string
     */
    public static function maskIdCard($id_card)
    {
        if ($id_card) {
            $len = strlen($id_card);
            if ($len == 15 || $len == 18) {
                $length = $len - 8;
                return StringUtil::mask($id_card, 4, $length);
            }
        }

        return $id_card;
    }

    /**
     * 遮蔽真实姓名
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $name
     *
     * @return string
     */
    public static function maskName($name)
    {
        if ($name) {
            $len = mb_strlen($name);
            if ($len > 2) {
                $length = $len - 2;
            } else if ($len > 1) {
                $length = 1;
            } else {
                return $name;
            }

            return StringUtil::mask($name, 1, $length);
        }

        return $name;
    }

    /**
     * 计算生肖
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $year
     *
     * @return string
     */
    public static function chineseZodiac($year)
    {
        $animals = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
        return $animals[($year - 4) % 12];
    }

    /**
     * 计算星座
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param integer $month
     * @param integer $day
     *
     * @return string
     */
    public static function horoscope($month, $day)
    {
        // $zodiac_signs和$zodiac_start_dates和$zodiac_end_dates，分别存储星座名称和每个星座的起始日期和结束日期。
        $zodiac_signs = array('水瓶座', '双鱼座', '白羊座', '金牛座', '双子座', '巨蟹座', '狮子座', '处女座', '天秤座', '天蝎座', '射手座', '摩羯座');
        $zodiac_start_dates = array(20, 19, 21, 20, 21, 21, 23, 23, 23, 22, 22, 22);
        $zodiac_end_dates = array(18, 20, 20, 20, 21, 22, 22, 22, 22, 21, 21, 19);

        if ($day < $zodiac_start_dates[$month - 1]) {
            $month--;
        }

        return $zodiac_signs[$month % 12];
    }
}
