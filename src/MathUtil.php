<?php

namespace Nece\Util;

/**
 * 数学计算工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class MathUtil
{
    /**
     * 除法计算
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param float $dividend 被除数
     * @param float $divisor 除数
     * @param int $precision 保留小数位数
     * 
     * @return float
     */
    public static function division($dividend, $divisor, $precision = 0)
    {
        if ($dividend == 0) {
            return 0;
        } else {
            return round($dividend / $divisor, $precision);
        }
    }

    /**
     * 生成随机小数
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param int $min 最小值
     * @param int $max 最大值
     * 
     * @return float
     */
    public static function rand($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
}
