<?php

namespace Nece\Util;

/**
 * 字符串工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class StringUtil
{
    /**
     * 只替换一次字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $needle 查找的目标值
     * @param string $replace 替换值
     * @param string $haystack 执行替换的数组或者字符串
     * 
     * @return string
     */
    public function strReplaceOnce($needle, $replace, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    /**
     * 检查字符串中是否包含某些字符串
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return boolean
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ('' != $needle && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串结尾
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return boolean
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === static::substr($haystack, -static::length($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串开头
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return boolean
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ('' != $needle && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }


    /**
     * 获取指定长度的随机字母数字组合的字符串
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param integer $length 长度
     * @param integer|null $type 0=纯字母，1=纯数字，2=全大写字母，3=全小写字母，4=汉字，默认大小写+数字
     * @param string $addChars 增加备选字符
     *
     * @return string
     */
    public static function random($length = 6,  $type = null,  $addChars = '')
    {
        $str = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书" . $addChars;
                break;
            default:
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($length > 10) {
            $chars = $type == 1 ? str_repeat($chars, $length) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $length);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $str .= mb_substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
     * 随机获取由数字组成的字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param int $length
     * 
     * @return string
     */
    public static function randNum($length)
    {
        mt_srand((float) microtime() * 1000000);
        $randval = '';
        for ($i = 0; $i < $length; $i++) {
            $randval .= mt_rand(0, 9);
        }
        return $randval;
    }

    /**
     * 获取字符串的长度
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $value
     *
     * @return integer
     */
    public static function length($value)
    {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $string
     * @param integer $start
     * @param integer|null $length
     *
     * @return string
     */
    public static function substr($string,  $start,  $length = null)
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 驼峰转下划线
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $value
     * @param string $delimiter
     *
     * @return string
     */
    public static function snake($value,  $delimiter = '_')
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return  $value;
    }

    /**
     * 下划线转驼峰(首字母小写)
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $value
     *
     * @return string
     */
    public static function camel($value)
    {
        return lcfirst(static::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     *
     * @param string $value
     *
     * @return string
     */
    public static function studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }

    /**
     * 从左边开始取字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str 原字符串
     * @param int $len 长度
     * 
     * @return string
     */
    public static function left($str, $len)
    {
        return substr($str, 0, $len);
    }

    /**
     * 从右边开始取字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str 原字符串
     * @param int $len 长度
     * 
     * @return string
     */
    public static function right($str, $len)
    {
        return substr($str, -1 * $len);
    }

    /**
     * 从原字符串中取出给定字符串的左边部分
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str 原字符串
     * @param string $find 给定字符串
     * @param bool $rev 是否反向查找
     * 
     * @return string
     */
    public static function leftPart($str, $find, $rev = false)
    {
        if (false !== ($pos = $rev ? strrpos($str, $find) : strpos($str, $find))) {
            $str = substr($str, 0, $pos);
        }
        return $str;
    }

    /**
     * 从原字符串中取出给定字符串的右边部分
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str 原字符串
     * @param string $find 给定字符串
     * @param bool $rev 是否反向查找
     * 
     * @return string
     */
    public static function rightPart($str, $find, $rev = false)
    {
        if (false !== ($pos = $rev ? strrpos($str, $find) : strpos($str, $find))) {
            $str = substr($str, $pos + strlen($find));
        }
        return $str;
    }

    /**
     * 从原字符串中取给定边界包围的子字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string 原字符串
     * @param string $start_string 开始边界
     * @param string $end_string 结束边界
     * @param string $offset 起始位置
     * 
     * @return string
     */
    public static function partBetween($string, $start_string, $end_string, &$offset = 0)
    {
        if (false !== ($start_pos = strpos($string, $start_string, $offset))) {
            $start_pos += strlen($start_string);
            if (false !== ($end_pos = strpos($string, $end_string, $start_pos))) {
                $offset = $end_pos;
                $string = substr($string, $start_pos, $end_pos - $start_pos);
            } else {
                $offset = $start_pos;
                $string = substr($string, $start_pos);
            }
            return $string;
        }
        return '';
    }

    /**
     * 全角转半角
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str
     * 
     * @return string
     */
    public static function toSemiangle($str)
    {
        $arr = array(
            '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
            '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ', '＄' => '$', '＠' => '@', '＃' => '#', '＾' => '^', '＆' => '&', '＊' => '*',
            '＂' => '"'
        );

        return strtr($str, $arr);
    }

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
     * 压缩字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $data 原字符串
     * 
     * @return string 压缩后的数据
     */
    public static function gzip($data)
    {
        return gzdeflate($data);
    }

    /**
     * 解压字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $data 压缩后的数据
     * 
     * @return string 原字符串
     */
    public static function ungzip($data)
    {
        return gzinflate($data);
    }

    /**
     * 压缩字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string 待压缩字符串
     * 
     * @return string
     */
    public static function stringCompress($string)
    {
        return base64_encode(self::gzip($string));
    }

    /**
     * 解压字符串
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $data 待解压字符串
     * 
     * @return string
     */
    public static function stringDecompress($data)
    {
        return self::ungzip(base64_decode($data));
    }

    /**
     * 测试字符串开头三个字符是不是BOM字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $contents 待测试字符串
     * 
     * @return boolean
     */
    public static function checkBOM($contents)
    {
        $charset[1] = substr($contents, 0, 1);
        $charset[2] = substr($contents, 1, 1);
        $charset[3] = substr($contents, 2, 1);
        if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
            return true;
        }
        return false;
    }

    /**
     * 汉字数字转阿拉伯数字
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $text 汉字数字
     * 
     * @return mixed
     */
    public static function toArabicNumber($text)
    {
        $cnb = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖", "拾", "佰", "仟", "万", "亿");
        $cns = array("零", "一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "百", "千", "万", "亿");
        $cna = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $npart = array_slice($cns, 0, 10);
        $upart = array_slice($cns, 10);

        $number = $result = 0;
        $times = 1;
        $unit = '';
        $text = str_replace($cnb, $cns, trim($text));

        $matches = array();
        if (preg_match('/(.*?)((?:' . implode('|', $upart) . ')*)$/', $text, $matches)) {
            $text = $matches[1];
            if (isset($matches[2])) {
                $unit = $matches[2];
            }
        }

        if (preg_match_all('/' . implode('|', $cns) . '/', $text, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $char = $matches[0][$i];
                if (in_array($char, $npart)) {
                    $number = 0;
                    if ($char == $npart[0]) {
                        $times = 1;
                        if (isset($matches[0][$i + 1])) {
                            $i++;
                            $number = str_replace($npart, $cna, $matches[0][$i]);
                        }
                    } else {
                        $number = str_replace($npart, $cna, $char);
                        if (isset($matches[0][$i + 1])) {
                            $i++;
                            switch ($matches[0][$i]) {
                                case $upart[0]:
                                    $times = 10;
                                    break;
                                case $upart[1]:
                                    $times = 100;
                                    break;
                                case $upart[2]:
                                    $times = 1000;
                                    break;
                                case $upart[3]:
                                    $times = 10000;
                                    break;
                                case $upart[4]:
                                    $times = 100000000;
                                    break;
                            }
                        } else {
                            if ($i > 0) {
                                $times /= 10;
                            }
                        }
                    }
                    $result += $number * $times;
                } elseif ($char == $upart[0]) {
                    $times = 1;
                    $result = 10;
                    if (isset($matches[0][$i + 1])) {
                        $i++;
                        $result += str_replace($npart, $cna, $matches[0][$i]);
                    }
                }
            }
        }

        if ($unit && preg_match_all('/(' . implode('|', $upart) . ')/', $unit, $matches)) {
            if ($result == 0) {
                $result = 1;
            }

            foreach ($matches[1] as $char) {
                switch ($char) {
                    case $upart[0]:
                        $result *= 10;
                        break;
                    case $upart[1]:
                        $result *= 100;
                        break;
                    case $upart[2]:
                        $result *= 1000;
                        break;
                    case $upart[3]:
                        $result *= 10000;
                        break;
                    case $upart[4]:
                        $result *= 100000000;
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * 文本中的汉字转阿拉伯数字
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $text 内容
     * 
     * @return string
     */
    public static function replaceToArabicNumber($text)
    {
        $cnb = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖", "拾", "佰", "仟", "万", "亿");
        $cns = array("零", "一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "百", "千", "万", "亿");
        $npart = array_slice($cns, 0, 10);
        $upart = array_slice($cns, 10);
        $text = str_replace($cnb, $cns, trim($text));

        if (preg_match_all('/((?:' . implode('|', $npart) . ')(?:' . implode('|', $cns) . ')*)/', $text, $matches)) {
            foreach ($matches[1] as $match) {
                $number = self::toArabicNumber($match);
                $text = str_replace($match, $number, $text);
            }
        }
        return $text;
    }

    /**
     * 清除字符串中的控制字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string
     * 
     * @return string
     */
    public static function clearCtrl($string)
    {
        $ctrls = array();
        for ($i = 0; $i < 21; $i++) {
            $ctrls[] = chr('0x' . $i);
        }

        return str_replace($ctrls, '', $string);
    }
}
