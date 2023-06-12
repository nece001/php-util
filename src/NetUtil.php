<?php

namespace Nece\Util;

/**
 * 网络工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class NetUtil
{
    /**
     * 随机制作一个IP
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @return string
     */
    public static function randIp()
    {
        $arr_1 = array("218", "218", "66", "66", "218", "218", "60", "60", "202", "204", "66", "66", "66", "59", "61", "60", "222", "221", "66", "59", "60", "60", "66", "218", "218", "62", "63", "64", "66", "66", "122", "211");
        $ip1id = $arr_1[array_rand($arr_1)];

        $ip2id = round(rand(600000, 2550000) / 10000);
        $ip3id = round(rand(600000, 2550000) / 10000);
        $ip4id = round(rand(600000, 2550000) / 10000);
        return $ip1id . '.' . $ip2id . '.' . $ip3id . '.' . $ip4id;
    }

    /**
     * 根据给定的主机名获取MX记录
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $hostname 主机名
     * @param array $mxhosts MX记录列表
     * @param array $weight MX记录优先级列表
     * 
     * @return boolean
     */
    public static function getMxrr($hostname, &$mxhosts, &$weight = false)
    {
        if (function_exists('getmxrr')) {
            return getmxrr($hostname, $mxhosts, $weight);
        } elseif (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            return self::getmxrrWin($hostname, $mxhosts, $weight);
        }
        return false;
    }

    /**
     * Win下根据给定的主机名获取MX记录
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $hostname 主机名
     * @param array $mxhosts MX记录列表
     * @param array $weight MX记录优先级列表
     * @return boolean
     */
    public static function getmxrrWin($hostname, &$mxhosts, &$mxweight = false)
    {
        if (!is_array($mxhosts))
            $mxhosts = array();
        if (empty($hostname))
            return;
        $exec = 'nslookup -type=MX ' . escapeshellarg($hostname);
        @exec($exec, $output);
        if (empty($output))
            return;
        $i = -1;
        foreach ($output as $line) {
            $i++;
            if (preg_match("/^$hostname\tMX preference = ([0-9]+), mail exchanger = (.+)$/i", $line, $parts)) {
                $mxweight[$i] = trim($parts[1]);
                $mxhosts[$i] = trim($parts[2]);
            }
            if (preg_match('/responsible mail addr = (.+)$/i', $line, $parts)) {
                $mxweight[$i] = $i;
                $mxhosts[$i] = trim($parts[1]);
            }
        }
        return ($i != -1);
    }
}
