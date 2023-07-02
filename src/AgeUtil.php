<?php

namespace Nece\Util;

/**
 * 年龄工具方法
 *
 * @Author nece001@163.com
 * @DateTime 2023-07-02
 */
class AgeUtil
{
    /**
     * 生日转年龄(周岁)
     *
     * @author gjw
     * @created 2023-02-13 14:59:57
     *
     * @param string $birthday
     * @param string $now
     * @return integer
     */
    public static function birthdayToAge($birthday, $now = null)
    {
        $birthday_time = DatetimeUtil::timestamp($birthday);
        if (!$birthday_time) {
            throw new \Exception('生日输入有误');
        }

        $now = DatetimeUtil::timestamp($now);
        if (!$now) {
            throw new \Exception('参照年份输入有误');
        }

        $birth_year = intval(DatetimeUtil::year($birthday_time));
        $now_year = intval(DatetimeUtil::year($now));
        $age = abs($now_year - $birth_year);

        $birth_date = DatetimeUtil::date($birthday_time, '1970-m-d');
        $now_date = DatetimeUtil::date($now, '1970-m-d');

        $birth_date_time = strtotime($birth_date);
        $now_date_time = strtotime($now_date);

        if ($birth_date_time > $now_date_time) {
            $age -= 1;
        }
        return $age;
    }

    
}
