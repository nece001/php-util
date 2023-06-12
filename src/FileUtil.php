<?php

namespace Nece\Util;

/**
 * 文件系统工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class FileUtil
{
    /**
     * 计算字节数,默认单位为M
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $size_string 空间大小字符串 如 5M
     * 
     * @return int
     */
    public static function byte($size_string)
    {
        if (preg_match('/(\d+)(\w?)/i', $size_string, $matches)) {
            $size = $matches[1];
            $unit = $matches[2] ? strtoupper($matches[2]) : 'M';
            switch ($unit) {
                case 'K':
                    return intval($size * 1024);
                case 'M':
                    return intval($size * 1048576);
                case 'G':
                    return intval($size * 1073741824);
                case 'T':
                    return intval($size * 1099511627776);
                default:
                    return $size;
            }
        }
        return 0;
    }

    /**
     * 计算所占存储空间大小
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param int $size 字节
     * 
     * @return string
     */
    public static function size($size)
    {
        $k = 1024;
        $m = 1048576;
        $g = 1073741824;

        if ($size >= $g) {
            return ceil($size / $g) . ' GB';
        }

        if ($size >= $m) {
            return ceil($size / $m) . ' MB';
        }

        if ($size >= $k) {
            return ceil($size / $k) . ' KB';
        }

        return $size . ' B';
    }

    /**
     * 从给出的路径获取文件名,即路径的最后一级
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $path 路径
     * 
     * @return string
     */
    public static function getFileNameFromPath($path)
    {
        if ($path) {
            $parts = explode('/', str_replace('\\', '/', $path));
            return end($parts);
        }
        return '';
    }

    /**
     * 获取文件扩展名(不含点)
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $filename 文件名
     * 
     * @return string
     */
    public static function getExtName($filename)
    {
        $dot_pos = strrpos($filename, '.');
        return strtolower(false !== $dot_pos ? substr($filename, $dot_pos + 1) : '');
    }

    /**
     * 获取去掉扩展名的文件名
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $filename 文件名
     * 
     * @return string
     */
    public static function getBaseName($filename)
    {
        if ($filename) {
            $filename = self::getFileNameFromPath($filename);
            $dot_pos = strrpos($filename, '.');
            return false !== $dot_pos ? substr($filename, 0, $dot_pos) : $filename;
        }
        return '';
    }

    /**
     * 生成随机文件名
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $ext 文件扩展名
     * 
     * @return string
     */
    public static function randFilename($ext)
    {
        return StringUtil::random(6) . StringUtil::randNum(5) . '.' . $ext;
    }

    /**
     * 获取临时目录
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @return string
     */
    public static function getTempDir()
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取一个临时文件名
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @return string
     */
    public static function getTempFilename()
    {
        return tempnam(self::getTempDir(), 'Tux');
    }

    /**
     * 修正路径,去除两端的/,如果是路径补齐最后一个/
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $path
     * @param bool $is_file 是文件路径
     * 
     * @return string
     */
    public static function fixPath($path, $is_file = false)
    {
        $path = trim(preg_replace('/\/{2,}/', '/', str_replace('\\', '/', $path)), '/');
        if (!$is_file) {
            $path .= '/';
        }
        return $path;
    }

    /**
     * 修正URL,把一个相对路径变成绝对路径
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $current 当前路径,即相对路径用于参照的绝对路径
     * @param string $target 相对路径
     * 
     * @return string
     */
    public static function fixUrl($current, $target)
    {
        $target = str_replace('\\', '/', $target);
        if (false === strpos($target, '://')) {
            $current = str_replace('\\', '/', $current);

            $url_parts = parse_url($current);
            $host = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['port']) ? ':' . $url_parts['port'] : '') . '/';

            if (0 === strpos($target, '/')) {
                $url_path = '';
            } else {
                if (isset($url_parts['path'])) {
                    $url_path = $url_parts['path'];
                    if (false !== strpos($url_path, '.')) {
                        $url_path = (false !== ($pos = strrpos($url_path, '/'))) ? substr($url_path, 0, $pos + 1) : $url_path;
                    }
                }
            }

            $parts = explode('/', $url_path . $target);
            $arcv = array();
            foreach ($parts as $value) {
                if ($value !== '' && $value != '.') {
                    if ($value == '..') {
                        array_pop($arcv);
                    } else {
                        $arcv[] = $value;
                    }
                }
            }

            return $host . implode('/', $arcv);
        }
        return $target;
    }

    /**
     * 读取CSV文件返回数组
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $filename 文件名
     * @param string $header 是否有表头
     * @return array
     * @throws \Exception
     */
    public static function csvLoad($filename, $header = true)
    {

        $content = file_get_contents($filename);
        if ($content) {
            $array = ArrayUtil::csvToArray($content);
            if ($header) {
                $data = array();
                $keys = array_shift($array);
                foreach ($array as $line) {
                    $row = array();
                    foreach ($line as $index => $value) {
                        $row[$keys[$index]] = $value;
                    }
                    $data[] = $row;
                }
                return $data;
            } else {
                return $array;
            }
        }
        return array();
    }

    /**
     * 数组保存为CSV文件
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $data 二维数组
     * @param string $filename 文件名
     * @param string $header 是否有表头
     * @throws \Exception
     */
    public static function csvSave($data, $filename, $header = true)
    {
        if ($data) {
            if ($header) {
                $th = array_keys(current($data));
                $data = array_merge(array($th), $data);
            }
            $bom = chr(239) . chr(187) . chr(191);
            $content = ArrayUtil::arrayToCsv($data);
            if (!file_put_contents($filename, $bom . $content)) {
                throw new \Exception('cannot write file:' . $filename);
            }
            return true;
        }
        return false;
    }

    /**
     * 输出下载CSV文件
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $data 二维数组
     * @param string $header 是否有表头
     */
    public static function csvDownload($data, $header = true)
    {
        $content = '';
        if ($data) {
            if ($header) {
                $th = array_keys(current($data));
                $data = array_merge(array($th), $data);
            }
            $content = ArrayUtil::arrayToCsv($data);
        }
        self::download(date('Y_m_d_H_i') . '.csv', $content);
    }

    /**
     * 文件下载输出
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $name 文件名
     * @param string $content 文件内容
     * @param string $type 内容类型
     */
    public static function download($name, $content, $type = 'application/octet-stream')
    {
        ob_clean();
        header("Content-type: " . $type);
        header("Content-Disposition: attachment; filename=" . $name);
        header("Content-Length: " . strlen($content));
        echo $content;
    }

    /**
     * Windows保存快捷方式
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $name 名称
     * @param string $url 网址
     */
    public static function windowsShortCut($name, $url)
    {
        $parts = parse_url($url);
        $domain = $parts['host'];

        $content = '[InternetShortcut]
URL=' . $url . '
IconFile=' . $parts['scheme'] . '://' . $domain . '/favicon.ico
IDList=
IconIndex=1
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2';
        self::download($name . '.url', $content);
    }
}
