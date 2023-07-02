<?php

namespace Nece\Util;

/**
 * 兼容java的工具方法
 *
 * @Author nece001@163.com
 * @DateTime 2023-07-02
 */
class JavaUtil
{
    /**
     * base64编码
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $string
     *
     * @return string
     */
    public static function base64Encode($string)
    {
        $base64 = base64_encode($string);
        return rtrim(strtr($base64, '+/', '-_'), '='); //参照Java sdk进行相应的字符替换，以求结果一致
    }

    /**
     * base64解码
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $string
     * @param boolean $strict 为 true 时，一旦输入的数据超出了 base64 字母表，将返回 false。 否则会静默丢弃无效的字符。
     *
     * @return mixed
     */
    public static function base64Decode($string, $strict = false)
    {
        $content = strtr($string, '-_', '+/');
        return base64_decode($content, $strict);
    }

    /**
     * 获取当前时间的时间戳
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @return integer
     */
    public static function timestamp()
    {
        //microtime — 返回当前 Unix 时间戳和微秒数
        //将微妙数和时间戳 赋值给 $t1 $t2
        list($t1, $t2) = explode(' ', microtime());
        return $t2  .  ceil(($t1 * 1000));
    }

    /**
     * PHP时间戳转Java时间戳
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param integer $timestamp
     *
     * @return integer
     */
    public static function toJavaTimestampe($timestamp)
    {
        return $timestamp * 1000;
    }

    /**
     * Java时间戳转PHP时间戳
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param integer $timestamp
     *
     * @return integer
     */
    public static function toPhpTimestamp($timestamp)
    {
        return $timestamp ? ceil($timestamp / 1000) : 0;
    }
}
