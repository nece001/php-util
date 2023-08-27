<?php

namespace Nece\Util;

/**
 * 数组工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class ArrayUtil
{

    /**
     * 解析参数字符串为数组
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str 字符串 'a=1&b=2&c=&d=3'
     * 
     * @return array
     */
    public static function parseToArray($str)
    {
        $ret = array();
        parse_str($str, $ret);
        return $ret;
    }

    /**
     * 去除两边的空白
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array|string $array 变量(传址)
     */
    public static function trimValue(&$array)
    {
        if (is_array($array)) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    self::trimValue($value);
                } else {
                    $value = trim($value);
                }
            }
        } else {
            $array = trim($array);
        }
    }

    /**
     * 检查一个数组是关联数组
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param array $array
     *
     * @return boolean
     */
    public static function isAssoc($array)
    {
        return is_array($array) && !(array_keys($array) === range(0, sizeof($array) - 1));
    }

    /**
     * 数组转成字符串表示
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param array $array
     *
     * @return string
     */
    public static function arrayExport($array)
    {
        return var_export($array, true);
    }

    /**
     * 根据路径获取多维数组指定键的值
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array 数组
     * @param mixed $key 键名路径: root/foo/bar => $array['root']['foo']['bar']
     * @param mixed $default 键不存在时返回的值
     * 
     * @return mixed
     */
    public static function getValue($array, $key, $default = null)
    {
        $parts = explode('/', trim(str_replace('.', '/', $key), '/'));
        $value = $array;
        foreach ($parts as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return $default;
            }
        }
        return $value;
    }

    /**
     * 获取整数值
     *
     * @Author nece001@163.com
     * @DateTime 2023-08-27
     *
     * @param array $array
     * @param mixed $key
     * @param integer $default
     *
     * @return void
     */
    public static function getInt($array, $key, $default = 0)
    {
        return intval(self::getValue($array, $key, $default));
    }

    /**
     * 获取布尔值
     *
     * @Author nece001@163.com
     * @DateTime 2023-08-27
     *
     * @param array $array
     * @param mixed $key
     * @param boolean $default
     *
     * @return void
     */
    public static function getBool($array, $key, $default = false)
    {
        return boolval(self::getValue($array, $key, $default));
    }

    /**
     * 递归的将数组合并成字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array 数组
     * @param string $separator 分隔符
     * 
     * @return string
     */
    public static function join(array $array, $separator = '')
    {
        $string = array();
        if (is_array($array)) {
            foreach ($array as $item) {
                if (is_array($item)) {
                    $string[] = self::join($item, $separator);
                } else {
                    $string[] = $item;
                }
            }
        } else {
            $string[] = $array;
        }
        return implode($separator, $string);
    }

    /**
     * XML文档转为数组
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $xml XML文档字符串
     * 
     * @return array
     */
    public static function xmlToArray($xml)
    {
        return $xml ? self::xmlToArrayElement(simplexml_load_string($xml)) : array();
    }

    /**
     * xml文档转为数组元素
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param obj $xmlobject XML文档对象
     * 
     * @return array
     */
    public static function xmlToArrayElement($xmlobject)
    {
        $data = array();
        foreach ((array) $xmlobject as $key => $value) {
            $node = !is_string($value) ? self::xmlToArrayElement($value) : $value;
            $data[$key] = empty($node) ? '' : $node;
        }
        return $data;
    }

    /**
     * 数组转XML,键名为标签名
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array 数据数组，CDATA节需强转为(object)类型
     * @param string $charset 编码
     * 
     * @return string
     */
    public static function arrayToXml(array $array, $charset = 'utf-8')
    {
        return '<?xml version="1.0" encoding="' . $charset . '"?>' . self::arrayToXmlDoc($array);
    }

    /**
     * 数组转XML文档,键名为标签名
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array 数据数组，CDATA节需强转为(object)类型
     * 
     * @return string
     */
    public static function arrayToXmlDoc(array $array)
    {
        $doc = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (is_string($key)) {
                    $doc[] = '<' . $key . '>' . self::arrayToXmlDoc($value) . '</' . $key . '>';
                } else {
                    $doc[] = self::arrayToXmlDoc($value);
                }
            } else {
                if (is_object($value) && isset($value->scalar)) {
                    $value = '<![CDATA[' . $value->scalar . ']]>';
                }
                $doc[] = '<' . $key . '>' . $value . '</' . $key . '>';
            }
        }
        return implode("", $doc);
    }

    /**
     * 数据组织为层级显示
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array
     * 
     * @return array
     */
    public static function toCascadeList($array)
    {
        foreach ($array as &$row) {
            $row['title'] = str_repeat('┊', $row['depth'] - 1) . '├' . $row['title'];
        }
        return $array;
    }

    /**
     * 数组转序列化列表
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array
     * 
     * @return string
     */
    public static function arrayToSerializationList(array $array)
    {
        return '|' . implode('|', $array) . '|';
    }

    /**
     * 序列化列表转数组
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $list
     * 
     * @return string
     */
    public static function serializationListToArray($list)
    {
        return explode('|', trim($list, '|'));
    }

    /**
     * 数组转为CSV格式
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array 二维数组
     * @param string $enclosure 包裹字符
     * @param string $delimiter 界定符
     * 
     * @return string
     * @throws \Exception
     */
    public static function arrayToCsv(array $array, $enclosure = '"', $delimiter = ",")
    {
        $data = array();
        foreach ($array as $row) {
            $line = array();
            if (is_array($row)) {
                foreach ($row as $cell) {
                    if (is_array($cell)) {
                        throw new \Exception('数组只接受两维');
                    }
                    $line[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $cell) . $enclosure;
                }
            } else {
                $line[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $row) . $enclosure;
            }
            $data[] = implode($delimiter, $line);
        }
        return implode("\r\n", $data);
    }

    /**
     * CSV字符串转为二维数组
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $csv CSV字符串
     * @param string $enclosure 包裹字符
     * @param string $delimiter 界定符
     * 
     * @return array
     */
    public static function csvToArray($csv, $enclosure = '"', $delimiter = ",")
    {
        $data = preg_split('/[\r\n]+/s', $csv);
        $tmp = array();
        foreach ($data as $line) {
            $tmp[] = str_getcsv($line, $delimiter, $enclosure);
        }
        return $tmp;
    }

    /**
     * 笛卡尔乘积
     *
     * @Author nece001@163.com
     * @DateTime 2023-08-03
     *
     * @param array $a
     * @param array $b
     * ...
     *
     * @return array
     */
    public static function arrayCartesianProduct($a, $b)
    {
        $list = func_get_args();
        $a = array();
        foreach ($list as $b) {
            $a = self::arrayCrossJoin($a, $b);
        }
        return $a;
    }

    /**
     * 两个数组笛卡尔乘积合并为一个
     *
     * @Author nece001@163.com
     * @DateTime 2023-08-03
     *
     * @param array $a
     * @param array $b
     *
     * @return array
     */
    public static function arrayCrossJoin($a, $b)
    {
        if (empty($a)) {
            if (empty($b)) {
                return [];
            } else {
                return is_array($b) ? $b : [$b];
            }
        }

        if (empty($b)) {
            if (empty($a)) {
                return [];
            } else {
                return is_array($a) ? $a : [$a];
            }
        }

        $data = [];
        foreach ($a as $i) {
            foreach ($b as $j) {
                $row = [];
                if (is_array($i)) {
                    if (is_array($j)) {
                        $row = array_merge($i, $j);
                    } else {
                        $row = $i;
                        $row[] = $j;
                    }
                } else {
                    if (is_array($j)) {
                        $row = $j;
                        array_unshift($row, $i);
                    } else {
                        $row = [$i, $j];
                    }
                }

                $data[] = $row;
            }
        }
        return $data;
    }
}
