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
     * 检查某时间($time)是否符合某个corntab时间计划($str_cron)
     *
     * @param int    $time     时间戳
     * @param string $str_cron corntab的时间计划，如，"30 2 * * 1-5"
     *
     * @return bool/string 出错返回string（错误信息）
     */
    static public function crontab($time, $str_cron) {
        $format_time = self::crontabFormatFimestamp($time);
        $format_cron = self::formatCrontab($str_cron);
        if (!is_array($format_cron)) {
            return $format_cron;
        }
        return self::crontabFormatCheck($format_time, $format_cron);
    }

    /**
     * 使用格式化的数据检查某时间($format_time)是否符合某个corntab时间计划($format_cron)
     *
     * @param array $format_time self::format_timestamp()格式化时间戳得到
     * @param array $format_cron self::format_crontab()格式化的时间计划
     *
     * @return bool
     */
    static public function crontabFormatCheck(array $format_time, array $format_cron) {
        return (!$format_cron[0] || in_array($format_time[0], $format_cron[0]))
            && (!$format_cron[1] || in_array($format_time[1], $format_cron[1]))
            && (!$format_cron[2] || in_array($format_time[2], $format_cron[2]))
            && (!$format_cron[3] || in_array($format_time[3], $format_cron[3]))
            && (!$format_cron[4] || in_array($format_time[4], $format_cron[4]))
        ;
    }

    /**
     * 格式化时间戳，以便比较
     *
     * @param int $time 时间戳
     *
     * @return array
     */
    static public function crontabFormatFimestamp($time) {
        return explode('-', date('i-G-j-n-w', $time));
    }

    /**
     * 格式化crontab时间设置字符串,用于比较
     *
     * @param string $str_cron crontab的时间计划字符串，如"15 3 * * *"
     *
     * @return array/string 正确返回数组，出错返回字符串（错误信息）
     */
    static public function formatCrontab($str_cron) {
        //格式检查
        $str_cron = trim($str_cron);
        $reg = '#^((\*(/\d+)?|((\d+(-\d+)?)(?3)?)(,(?4))*))( (?2)){4}$#';
        if (!preg_match($reg, $str_cron)) {
            return '格式错误';
        }

        try{
            //分别解析分、时、日、月、周
            $arr_cron = array();
            $parts = explode(' ', $str_cron);
            $arr_cron[0] = self::parseCronPart($parts[0], 0, 59);//分
            $arr_cron[1] = self::parseCronPart($parts[1], 0, 59);//时
            $arr_cron[2] = self::parseCronPart($parts[2], 1, 31);//日
            $arr_cron[3] = self::parseCronPart($parts[3], 1, 12);//月
            $arr_cron[4] = self::parseCronPart($parts[4], 0, 6);//周（0周日）
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $arr_cron;
    }

    /**
     * 解析crontab时间计划里一个部分(分、时、日、月、周)的取值列表
     * @param string $part  时间计划里的一个部分，被空格分隔后的一个部分
     * @param int    $f_min 此部分的最小取值
     * @param int    $f_max 此部分的最大取值
     *
     * @return array 若为空数组则表示可任意取值
     * @throws Exception
     */
    static protected function parseCronPart($part, $f_min, $f_max) {
        $list = array();

        //处理"," -- 列表
        if (false !== strpos($part, ',')) {
            $arr = explode(',', $part);
            foreach ($arr as $v) {
                $tmp  = self::parseCronPart($v, $f_min, $f_max);
                $list = array_merge($list, $tmp);
            }
            return $list;
        }

        //处理"/" -- 间隔
        $tmp  = explode('/', $part);
        $part  = $tmp[0];
        $step = isset($tmp[1]) ? $tmp[1] : 1;

        //处理"-" -- 范围
        if (false !== strpos($part, '-')) {
            list($min, $max) = explode('-', $part);
            if ($min > $max) {
                throw new \Exception('使用"-"设置范围时，左不能大于右');
            }
        } elseif ('*' == $part) {
            $min = $f_min;
            $max = $f_max;
        } else {//数字
            $min = $max = $part;
        }

        //空数组表示可以任意值
        if ($min==$f_min && $max==$f_max && $step==1) {
            return $list;
        }

        //越界判断
        if ($min < $f_min || $max > $f_max) {
            throw new \Exception('数值越界。应该：分0-59，时0-59，日1-31，月1-12，周0-6');
        }

        return $max-$min>$step ? range($min, $max, $step) : array((int)$min);
    }
}
