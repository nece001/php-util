<?php

namespace Nece\Util;

/**
 * 日期时间工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class DatetimeUtil
{
    private static $timer_start;

    /**
     * 获取时间戳，默认为当前时间
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $datetime 时间字符串
     * @return int 成功返回时间戳
     */
    public static function timestamp($datetime = null)
    {
        if ($datetime) {
            return is_string($datetime) ? strtotime($datetime) : $datetime;
        } else {
            return time();
        }
    }

    /**
     * 获取当前时间
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @return string
     */
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * 获取年
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function year($time = null)
    {
        return date('Y', self::timestamp($time));
    }

    /**
     * 获取月
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function month($time = null)
    {
        return date('m', self::timestamp($time));
    }

    /**
     * 获取日
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function day($time = null)
    {
        return date('d', self::timestamp($time));
    }

    /**
     * 获取时
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function hour($time = null)
    {
        return date('h', self::timestamp($time));
    }

    /**
     * 获取分
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function minute($time = null)
    {
        return date('i', self::timestamp($time));
    }

    /**
     * 获取秒
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function second($time = null)
    {
        return date('s', self::timestamp($time));
    }

    /**
     * 获取年月
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function yearMonth($time = null)
    {
        return date('Y-m', self::timestamp($time));
    }

    /**
     * 获取年月日
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time 默认当前时间
     *
     * @return string
     */
    public static function date($time = null)
    {
        return date('Y-m-d', self::timestamp($time));
    }

    /**
     * 格式化为日期格式
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string|int $time 时间
     * @param string $default 默认输出
     * 
     * @return string
     */
    public static function formatTodate($time, $default = '-')
    {
        return self::format($time, 'Y-m-d', $default);
    }

    /**
     * 格式化时间
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string|int $time 时间
     * @param string $format 格式字符串
     * @param string $default 默认输出
     * @return string
     */
    public static function format($time, $format = 'Y-m-d H:i:s', $default = '-')
    {
        if ($time) {
            $time = self::timestamp($time);
            return $time ? date($format, $time) : $default;
        }
        return $default;
    }

    /**
     * 格式化为时间格式
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string|int $time 时间
     * @param string $default 默认输出
     * @return string
     */
    public static function time($time, $default = '-')
    {
        return self::format($time, 'H:i:s', $default);
    }

    /**
     * 增加时间
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param int $value 增加值
     * @param string $type 单位:s,i,h,d,w,m,y
     * @param string|int $time 时间
     * @return int 成功返回时间戳，失败返回false
     */
    public static function add($value, $type = 'd', $time = null)
    {
        $timea = self::timestamp($time);
        switch (strtolower($type)) {
            case 'i':
                $type = 'minute';
            case 'h':
                $type = 'hour';
            case 'd':
                $type = 'day';
            case 'w':
                $type = 'week';
            case 'm':
                $type = 'month';
            case 'y':
                $type = 'year';
            default:
                $type = 'second';
        }
        return strtotime($value . ' ' . $type, $timea);
    }

    /**
     * 求两个时间之差
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string|int $time1 第一个时间
     * @param string|int $time2 第二个时间
     * @param string $type 单位:s,i,h,d,w,m,y
     * 
     * @return float|boolean 成功返回数量，失败返回false
     */
    public static function diff($time1, $time2, $type = 'd')
    {
        $timea = self::timestamp($time1);
        $timeb = self::timestamp($time2);
        if ($timea !== false && $timeb !== false) {
            $sec = abs($timea - $timeb);
            switch (strtolower($type)) {
                case 's':
                    return $sec;
                case 'm':
                    return $sec ? round($sec / 60) : 0;
                case 'h':
                    return $sec ? round($sec / 3600) : 0;
                case 'd':
                    return $sec ? round($sec / 86400) : 0;
                case 'w':
                    return $sec ? round($sec / 604800) : 0;
                case 'm':
                    return $sec ? round($sec / 2592000) : 0;
                case 'y':
                    return $sec ? round($sec / 31536000) : 0;
                default:
                    return $sec;
            }
        }
        return false;
    }

    /**
     * 时间1是否早于时间2
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time1
     * @param string|int $time2
     *
     * @return boolean
     */
    public static function isBefore($time1, $time2)
    {
        if (!$time1) {
            return false;
        }

        if (!$time2) {
            return true;
        }

        $t1 = self::timerStart($time1);
        $t2 = self::timerStart($time2);

        return $t1 < $t2;
    }

    /**
     * 时间1是否晚于时间2
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string|int $time1
     * @param string|int $time2
     *
     * @return boolean
     */
    public static function isAfter($time1, $time2)
    {
        if (!$time1) {
            return false;
        }

        if (!$time2) {
            return true;
        }

        $t1 = self::timerStart($time1);
        $t2 = self::timerStart($time2);

        return $t1 > $t2;
    }

    /**
     * 测试时间在给出的两个时间之间
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string|int $time 待测试时间
     * @param string|int $stime 开始时间
     * @param string|int $etime 结束时间
     * @return boolean
     */
    public static function between($time, $stime, $etime)
    {
        $timea = self::timestamp($time);

        if ($stime && $etime) {
            $timeb = self::timestamp($stime);
            $timec = self::timestamp($etime);
            if ($timeb > $timec) {
                return $timeb <= $timea && $timea <= $timec;
            }
            return $timec <= $timea && $timea <= $timeb;
        }

        if ($stime) {
            return self::isAfter(self::timestamp($stime), $timea);
        }

        if ($etime) {
            return self::isBefore(self::timestamp($etime), $timea);
        }

        return $time ? false : true;
    }

    /**
     * 设置一个定时点
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $name 定时点名称
     */
    public static function timerStart($name = '0')
    {
        self::$timer_start[$name] = microtime(true);
    }

    /**
     * 取计时结束间隔时间(毫秒)
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $name 定时点名称
     * @param int $precision 保留小数位数
     * 
     * @return int
     */
    public static function timerEndDiff($name = '0', $precision = 4)
    {
        $end_time = microtime(true);
        return round(($end_time - self::$timer_start[$name]) * 1000, $precision);
    }

    /**
     * 获取指定日期所在星期的起止日期范围
     *
     * @author gjw
     * @created 2023-06-12
     *
     * @param string|integer $time
     * @param boolean $is_mon_start
     * @return array
     */
    public static function getWeekRange($time = null, $is_mon_start = true)
    {
        $timestamp = self::timestamp($time);
        $week_day = intval(date('w', $timestamp));
        $week_day = $week_day === 0 ? 7 : $week_day;
        if ($is_mon_start) {
            $diff = $week_day - 1;
        } else {
            $diff = $week_day;
        }

        $start = strtotime('-' . $diff . ' day', $timestamp);
        $end = strtotime('+6 day', $start);
        return array(
            date('Y-m-d', $start),
            date('Y-m-d', $end)
        );
    }

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
        $birthday_time = self::timestamp($birthday);
        if (!$birthday_time) {
            throw new \Exception('生日输入有误');
        }

        $now = self::timestamp($now);
        if (!$now) {
            throw new \Exception('参照年份输入有误');
        }

        $birth_year = intval(self::year($birthday_time));
        $now_year = intval(self::year($now));
        $age = abs($now_year - $birth_year);

        $birth_date = self::date($birthday_time, '1970-m-d');
        $now_date = self::date($now, '1970-m-d');

        $birth_date_time = strtotime($birth_date);
        $now_date_time = strtotime($now_date);

        if ($birth_date_time > $now_date_time) {
            $age -= 1;
        }
        return $age;
    }
}
