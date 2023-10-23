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

    /**
     * ICMP请求
     * 参考：
     * https://www.qyyshop.com/info/121609.html
     *
     * @author gjw
     * @created 2023-10-23 16:17:59
     *
     * @param string $host
     * @return array
     */
    public static function icmp($host)
    {
        $data = array();
        $write = null;
        $except = null; //初始化所需变量
        $package = chr(8) . chr(0); //模式 8 0
        $package .= chr(0) . chr(0); //置零校验和
        $package .= "R" . "C"; //ID
        $package .= chr(0) . chr(1); //序列号
        for ($i = strlen($package); $i < 64; $i++) {
            $package .= chr(0);
        }
        $tmp = unpack("n*", $package); //把数据16位一组放进数组里
        $sum = array_sum($tmp); //求和
        $sum = ($sum >> 16) + ($sum & 0xFFFF); //结果右移十六位 加上结果与0xFFFF做AND运算
        $sum = $sum + ($sum >> 16); //结果加上结果右移十六位
        $sum = ~$sum; //做NOT运算
        $checksum = pack("n*", $sum); //打包成2字节
        $package[2] = $checksum[0];
        $package[3] = $checksum[1]; //填充校验和

        $socket = socket_create(AF_INET, SOCK_RAW, getprotobyname('icmp')); //创建原始套接字
        $start = microtime(true); //记录开始时间
        @socket_sendto($socket, $package, strlen($package), 0, $host, 0); //发送数据包
        $read = array($socket); //初始化socket
        $select = socket_select($read, $write, $except, 5);
        if ($select === false) {
            socket_close($socket);
            throw new \Exception("socket_select()方法发生错误，原因:" . socket_strerror(socket_last_error()));
        } else if ($select === 0) {
            // $g_icmp_error = "请求超时";
            socket_close($socket);
            throw new \Exception('请求超时');
        } else {
            socket_recvfrom($socket, $recv, 65535, 0, $host, $port); //接受回传数据
            $end = microtime(true); //记录结束时间
            $time = round(($end - $start) * 1000, 3); //计算耗费的时间

            /*回传数据处理*/
            $recv = unpack("C*", $recv);
            $length = count($recv) - 20; //包长度 减去20字节IP报头
            $ttl = $recv[9]; //ttl
            $seq = $recv[28]; //序列号

            $data = array(
                'length' => $length,
                'host' => $host,
                'ttl' => $ttl,
                'seq' => $seq,
                'time' => $time,
            );
        }
        socket_close($socket); //关闭socket
        return $data;
    }

    /**
     * Ping结果
     *
     * @author gjw
     * @created 2023-10-23 16:19:11
     *
     * @param string $host 
     * @param integer $retry
     * @return array
     */
    public static function ping($host, $retry = 1)
    {
        $data = array();
        for ($i = 0; $i < $retry; $i++) {
            try {
                $row = self::icmp($host);
                $data[] = "{$row['length']} bytes from {$row['host']}: icmp_seq={$row['seq']}  ttl={$row['ttl']} time={$row['time']}ms";
            } catch (\Throwable $e) {
                $data[] = $e->getMessage();
            }
        }
        return $data;
    }
}
