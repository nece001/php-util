<?php

namespace Nece\Util;

/**
 * 命令行工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class CliUtil
{
    /**
     * 从命令行获取输入
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param int $length 内容长度
     * @return string
     */
    static function scan($length = 1024)
    {
        return fread(STDIN, $length);
    }

    /**
     * 输出行
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string
     * @param bool $exit 是否结束程序
     */
    static function println($string, $exit = false)
    {
        echo $string . "\r\n";
        if ($exit) {
            exit();
        }
    }

    /**
     * 处理进度
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param int $max 最大值
     * @param int $current 当前值
     */
    static function progress($max, $current)
    {
        $p = 0;
        if ($current) {
            $p = round($current / $max * 100, 2);
        }
        printf("progress: [%-50s] %d%%\r", str_repeat('#', ceil($p / 2)), $p);
        if ($current == $max) {
            echo "\n";
        }
    }

    /**
     * 执行系统命令
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $cmd 命令
     * @return array
     */
    public static function cmdExec($cmd)
    {
        $cmd = escapeshellcmd($cmd);
        $result = array();
        $line = '';
        $state = 0;
        if (function_exists('exec')) {
            $line = exec($cmd, $result, $state);
        } elseif (function_exists('system')) {
            ob_start();
            $line = system($cmd, $state);
            $result = preg_split('/\r?\n/', trim(ob_get_clean()));
        } elseif (function_exists('passthru')) {
            ob_start();
            $line = passthru($cmd, $state);
            $result = preg_split('/\r?\n/', trim(ob_get_clean()));
        } elseif (function_exists('shell_exec')) {
            $ret = shell_exec($cmd);
            $result = preg_split('/\r?\n/', trim($ret));
        }

        if ($state != 0) {
            throw new \Exception('Command Execute Failed. ' . $cmd);
        }

        return $result;
    }
}
