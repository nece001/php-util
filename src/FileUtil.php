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
     * 读取文件内容
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $filename
     *
     * @return string
     */
    public static function read($filename)
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }

    /**
     * 写入文件
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $filename
     * @param string $writetext
     * @param string $openmod
     *
     * @return bool
     */
    public static function write($filename, $writetext, $openmod = 'w')
    {
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除文件或清空目录（不删除目录本身）
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dirname
     *
     * @return boolean
     */
    public static function clear($dirname)
    {
        if (!file_exists($dirname)) {
            return false;
        }

        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }

        $dir = dir($dirname);
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            self::clear($dirname . DIRECTORY_SEPARATOR . $entry);
        }
        $dir->close();
    }

    /**
     * 删除文件或目录（目录也删除）
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @param string $dirname
     *
     * @return boolean
     */
    public static function rm($dirname)
    {
        self::clear($dirname);
        return rmdir($dirname);
    }

    /**
     * 删除文件或目录（目录也删除）
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dirname
     *
     * @return boolean
     */
    public static function delete($dirname)
    {
        return self::rm($dirname);
    }

    /**
     * 复制
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $src 源文件或目录的路径
     * @param string $dist 目标路径
     *
     * @return boolean
     */
    public static function copy($src, $dist)
    {
        if (!file_exists($src)) {
            return false;
        }

        if (is_file($src)) {
            return copy($src, $dist);
        }

        $src = self::fixFilePath($src);
        $dist = self::fixFilePath($dist);
        if (!file_exists($dist)) {
            self::mkdir($dist);
        }

        $file = opendir($src);
        while ($fileName = readdir($file)) {
            $file1 = $src . DIRECTORY_SEPARATOR . $fileName;
            $file2 = $dist . DIRECTORY_SEPARATOR . $fileName;

            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copy($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }

    /**
     * 创建文件夹
     *
     * @param $dir
     * @return bool
     */
    public static function mkdir($dir)
    {
        $dir = self::fixFilePath($dir);
        if (!is_dir($dir)) {
            return mkdir($dir, 0700, true);
        }
        return true;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $path
     * @param string $preg
     *
     * @return array
     */
    public static function listFile($path, $preg = "/\.(gif|jpeg|jpg|png|bmp)$/i")
    {
        $files = array();

        if (!is_dir($path)) {
            return $files;
        }

        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . DIRECTORY_SEPARATOR . $file;

                if (is_dir($path2)) {
                    $tmp = self::listFile($path2, $files);
                    $files = array_merge($files, $tmp);
                } else {
                    if ($preg) {
                        if (preg_match($preg, $file)) {
                            $files[] = $path2;
                        }
                    } else {
                        $files[] = $path2;
                    }
                }
            }
        }
        return $files;
    }

    /**
     * 获取指定目录下的子目录和文件
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dir
     *
     * @return array
     */
    public static function listDir($dir)
    {
        $dir = self::fixFilePath($dir);
        $dirArray = [];
        if (false != ($handle = opendir($dir))) {
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, array('.', '..'))) {
                    if (is_dir($dir . $file)) { //判断是否文件夹
                        $dirArray['dir'][] = $file;
                    } else {
                        $dirArray['file'][] = $file;
                    }
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * 计算目录空间大小（字节数）
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dir
     *
     * @return int
     */
    public static function dirSize($dir)
    {
        if (!self::readable($dir)) {
            return 0;
        }

        $dir_list = opendir($dir);
        $dir_size = 0;
        while (false !== ($folder_or_file = readdir($dir_list))) {
            if ($folder_or_file != "." && $folder_or_file != "..") {
                if (is_dir("$dir/$folder_or_file")) {
                    $dir_size += self::dirSize("$dir/$folder_or_file");
                } else {
                    $dir_size += filesize("$dir/$folder_or_file");
                }
            }
        }
        closedir($dir_list);
        return $dir_size;
    }

    /**
     * 是否可读
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dir
     *
     * @return boolean
     */
    public static function readable($fiename)
    {
        return is_readable($fiename);
    }

    /**
     * 是否可写
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dir
     *
     * @return boolean
     */
    public static function writeable($dir)
    {
        if (is_dir($dir)) {
            if (is_writable($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (!self::writeable($dir . DIRECTORY_SEPARATOR . $object)) {
                            return false;
                        }
                    }
                }
                return true;
            } else {
                return false;
            }
        } else if (file_exists($dir)) {
            return (is_writable($dir));
        }
    }

    /**
     * 检测是否为空文件夹
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $dir
     *
     * @return boolean
     */
    public static function emptyDir($dir)
    {
        return (($files = @scandir($dir)) && count($files) <= 2);
    }

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
     * 修正文件路径
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-27
     *
     * @param string $path 路径
     * @param boolean $is_dir 是否文件
     *
     * @return string
     */
    public static function fixFilePath($path, $is_file = false)
    {
        $path = rtrim(str_replace('/', DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        if (!$is_file) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path;
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
    public static function fixUriPath($path, $is_file = false)
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
    public static function buildAbsoluteUrl($current, $target)
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

    /**
     * 读取SQL文件
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-30
     *
     * @param string $filename 文件名
     *
     * @return array
     */
    public function loadSql($filename)
    {
        $content = file_get_contents($filename);

        $content = preg_replace(array('#\/\*.*?\*\/\s*#ims', '/--\s[^\'\"]+\n/iU'), array('', ''), $content); // 清理注释
        $matches = array();
        $match = preg_match_all("@([\s\S]+?;)\h*[\n\r]@", $content, $matches); // 数据以分号;\n\r换行  为分段标记
        if ($match) {
            return $matches[1];
        }

        return array();
    }
}
