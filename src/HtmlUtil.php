<?php

namespace Nece\Util;

/**
 * HTML工具类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-12
 */
class HtmlUtil
{
    /**
     * 清除全部或指定的HTML标签
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $str 字符串
     * @param array $tags_a 指定的标签名称数组: array('a', 'img')
     * 
     * @return string
     */
    public static function stripTags($str, $tags_a = null)
    {
        if (null == $tags_a) {
            return strip_tags($str);
        } else {
            $p = array();
            foreach ($tags_a as $tag) {
                $p[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/i";
            }
            return preg_replace($p, '', $str);
        }
    }

    /**
     * 获取内容中的所有连接地址
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $content 内容
     * 
     * @return array
     */
    public static function fetchUrls($content)
    {
        $patt = '/<a.*?(?:\s)?href=[\'"]?\s*?((?!javascript:|mailto:|#)[^ \'">]+)\s*?[\'"]?(?:(?:\s)+.*?)?>/si';
        if (preg_match_all($patt, $content, $matchs)) {
            return array_unique($matchs[1]);
        }
        return array();
    }

    /**
     * 获取内容中的所有图片地址
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $content 内容
     * 
     * @return array
     */
    public static function fetchImgs($content)
    {
        $patt = '/<img.*?src=[\'"]?\s*?([^ \'">]+)\s*?[\'"]?(?:(?:\s)+.*?)?>/is';
        if (preg_match_all($patt, $content, $matchs)) {
            return array_unique($matchs[1]);
        }
        return array();
    }

    /**
     * HTML代码转成JS的write方法代码
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $code HTML
     * 
     * @return string
     */
    public static function toJsWrite($code)
    {
        $lines = preg_split('/\r?\n/', $code);
        $code = array();
        foreach ($lines as $line) {
            $code[] = "document.write('" . str_replace(array("\\", "'", "/"), array("\\\\", "\\'", "\/"), trim($line)) . "');";
        }
        return implode("\r\n", $code);
    }

    /**
     * HTML代码转成JS的字符串方法代码
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $code HTML
     * 
     * @return string
     */
    public static function toJsString($code)
    {
        $lines = preg_split('/\r?\n/', $code);
        $code = array();
        foreach ($lines as $line) {
            $code[] = str_replace(array("\\", "'", "/"), array("\\\\", "\\'", "\/"), trim($line));
        }
        return implode("\\\r\n", $code);
    }

    /**
     * 把字符串中的&转为&amp;
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string
     * 
     * @return string
     */
    public static function ampersand($string)
    {
        $string = str_replace('&amp;', '#amp;', $string);
        $string = str_replace('&', '&amp;', $string);
        return str_replace('#amp;', '&amp;', $string);
    }

    /**
     * 将html中的HTML实体转换成字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $html
     * 
     * @return string
     */
    public static function entityToChar($html, $out_charset)
    {
        $entitys = array();
        $replace = array();
        if (preg_match_all('/&#(\d{5});/', $html, $matchs)) {
            for ($i = 0; $i < count($matchs[0]); $i++) {
                $char = (int) $matchs[1][$i];
                $entitys[] = $matchs[0][$i];
                $replace[] = mb_convert_encoding($matchs[0][$i], $out_charset, "HTML-ENTITIES");
            }
        }

        return str_replace($entitys, $replace, $html);
    }

    /**
     * 把一些预定义的字符转换为 HTML 实体
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string 字符串
     * 
     * @return string
     */
    public static function specialcharsEncode($string)
    {
        return str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $string);
    }

    /**
     * 预定义的 HTML 实体转换为字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string 字符串
     * 
     * @return string
     */
    public static function specialcharsDecode($string)
    {
        return str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), array(' ', '&', '"', "'", '<', '>'), $string);
    }

    /**
     * 统计内容中包含的特定标记的数量
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $subject 内容
     * @param string $tag_name 标记名
     * 
     * @return int
     */
    public static function countTags($subject, $tag_name = '')
    {
        $tag_name = $tag_name ? $tag_name : '[a-z0-9]+';
        return preg_match_all('/<' . $tag_name . '[^>]*>/i', $subject);
    }

    /**
     * 生成HTML摘要
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $html html内容
     * @param int $max 摘要长度
     * @param string $suffix 后缀
     * 
     * @return string
     */
    public static function summary($html, $max, $suffix = '')
    {
        $non_paired_tags = array('br', 'hr', 'img', 'input', 'param'); // 非成对标签
        $html = trim($html);
        $count = 0; // 有效字符计数(一个HTML实体字符算一个有效字符)
        $tag_status = 0; // (0:非标签, 1:标签开始, 2:标签名开始, 3:标签名结束)
        $nodes = array(); // 存放解析出的节点(文本节点:array(0, '文本内容', 'text', 0), 标签节点:array(1, 'tag', 'tag_name', '标签性质:0:非成对标签,1:成对标签的开始标签,2:闭合标签'))
        $segment = ''; // 文本片段
        $tag_name = ''; // 标签名
        for ($i = 0; $i < strlen($html); $i++) {
            $char = $html[$i]; // 当前字符

            $segment .= $char; // 保存文本片段

            if ($tag_status == 4) {
                $tag_status = 0;
            }

            if ($tag_status == 0 && $char == '<') {
                // 没有开启标签状态,设置标签开启状态
                $tag_status = 1;
            }

            if ($tag_status == 1 && $char != '<') {
                // 标签状态设置为开启后,用下一个字符来确定是一个标签的开始
                $tag_status = 2; //标签名开始
                $tag_name = ''; // 清空标签名
                // 确认标签开启,将标签之前保存的字符版本存为文本节点
                $nodes[] = array(0, substr($segment, 0, strlen($segment) - 2), 'text', 0);
                $segment = '<' . $char; // 重置片段,以标签开头
            }

            if ($tag_status == 2) {
                // 提取标签名
                if ($char == ' ' || $char == '>' || $char == "\t") {
                    $tag_status = 3; // 标签名结束
                } else {
                    $tag_name .= $char; // 增加标签名字符
                }
            }

            if ($tag_status == 3 && $char == '>') {
                $tag_status = 4; // 重置标签状态
                $tag_name = strtolower($tag_name);

                // 跳过成对标签的闭合标签
                $tag_type = 1;
                if (in_array($tag_name, $non_paired_tags)) {
                    // 非成对标签
                    $tag_type = 0;
                } elseif ($tag_name[0] == '/') {
                    $tag_type = 2;
                }

                // 标签结束,保存标签节点
                $nodes[] = array(1, $segment, $tag_name, $tag_type);
                $segment = ''; // 清空片段
            }

            if ($tag_status == 0) {
                //echo $char.')'.$count."\n";
                if ($char == '&') {
                    // 处理HTML实体,10个字符以内碰到';',则认为是一个HTML实体
                    for ($e = 1; $e <= 10; $e++) {
                        if ($html[$i + $e] == ';') {
                            $segment .= substr($html, $i + 1, $e); // 保存实体
                            $i += $e; // 跳过实体字符所占长度
                            break;
                        }
                    }
                } else {
                    // 非标签情况下检查有效文本
                    $char_code = ord($char); // 字符编码
                    if ($char_code >= 224) { // 三字节字符
                        $segment .= $html[$i + 1] . $html[$i + 2]; // 保存字符
                        $i += 2; // 跳过下2个字符的长度
                    } elseif ($char_code >= 129) { // 双字节字符
                        $segment .= $html[$i + 1];
                        $i += 1; // 跳过下一个字符的长度
                    }
                }

                $count++;
                if ($count == $max) {
                    $nodes[] = array(0, $segment . $suffix, 'text');
                    break;
                }
            }
        }

        $html = '';
        $tag_open_stack = array(); // 成对标签的开始标签栈
        for ($i = 0; $i < count($nodes); $i++) {
            $node = $nodes[$i];
            if ($node[3] == 1) {
                array_push($tag_open_stack, $node[2]); // 开始标签入栈
            } elseif ($node[3] == 2) {
                array_pop($tag_open_stack); // 碰到一个结束标签,出栈一个开始标签
            }
            $html .= $node[1];
        }

        while ($tag_name = array_pop($tag_open_stack)) { // 用剩下的未出栈的开始标签补齐未闭合的成对标签
            $html .= '</' . $tag_name . '>';
        }
        return $html;
    }

    /**
     * 以HTML形式输出树型结构
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param array $array
     * $array = array(
     *   array(depth, title, url, target),
     *   array(depth, title, url, target),
     *   ...
     * );
     * @param bool $add_fix 是否添加前缀
     */
    public static function echoTree($array, $add_fix = true)
    {
        $end = 0;
        foreach ($array as $row) {
            $depth = (int) $row['depth'];
            if ($end < $depth) {
                echo '<div>';
            } elseif ($end > $depth) {
                echo '</div>';
            }

            $end = $depth;
            $title = $add_fix ? ($depth ? str_repeat('┆', $depth) : '') . '├ ' . $row['title'] : $row['title'];
            if ($row['url']) {
                $target = isset($row['target']) ? ' target="' . $row['target'] . '"' : '';
                echo '<a href="' . $row['url'] . '"' . $target . '>' . $title . '</a>';
            } else {
                echo '<span>' . $title . '</span>';
            }
        }
    }

    /**
     * 压缩合并CSS文件
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $filename 文件路径
     * @return string
     */
    public static function cssCompress($filename)
    {
        $filename = str_replace('\\', '/', $filename);
        $contents = file_get_contents($filename);
        self::cssMerge(substr($filename, 0, strrpos($filename, '/') + 1), $contents);
        return preg_replace('/\/\*.*\*\/\s*/s', '', $contents);
    }

    /**
     * 合并CSS文件,即把css文件中的引用的文件合并过来
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $path 文件基路径
     * @param string $contents 原始文件内容
     */
    public static function cssMerge($path, &$contents)
    {
        $count = preg_match_all('/@import url\(([^()]*)\)/i', $contents, $matches);
        if ($count) {
            for ($i = 0; $i < $count; $i++) {
                $file = $path . $matches[1][$i];
                $contents = str_replace($matches[0][$i], file_get_contents($file), $contents);
            }
            self::cssMerge($path, $contents);
        }
    }

    /**
     * 清除字符串中的脚本和样式代码
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string 字符串
     * 
     * @return string
     */
    public static function clearJsAndCss($string)
    {
        $string = preg_replace('/<script(.*?)<\/script>/is', '', $string);
        $string = preg_replace('/<style(.*?)<\/style>/is', '', $string);
        return $string;
    }

    /**
     * 清除所有段落开头用于缩进的空格
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $string
     * @return string
     */
    public static function clearIndent($string)
    {
        return preg_replace(array('/^(&nbsp;| |　)*/', '/\n(&nbsp;| )*/'), array('', "\n"), $string);
    }

    /**
     * 清除HTML实体
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $html HTML
     * @return string
     */
    public static function clearEntitys($html)
    {
        return preg_replace('/(&|#)[^;]+;/', '', $html);
    }

    /**
     * 内容中的清理标签属性
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $content 内容
     * @param string $allow 保留的属性名称
     * @return string
     */
    public static function clearTagAttrs($content, $allow = array())
    {
        $matchs = array();
        $allow_attr = implode('|', $allow);
        $pattern = '/<([^\s\/>]+)(?:[^>]+?((?:' . $allow_attr . ')=[\'"]?[^\'"]+[\'"]?)|[^>]*)[^>]*?(\/?>)/is';
        preg_match_all($pattern, $content, $matchs);
        foreach ($matchs[0] as $key => $tag) {
            if ($matchs[2][$key]) {
                $ntag = '<' . $matchs[1][$key] . ' ' . $matchs[2][$key] . ' ' . $matchs[3][$key];
            } else {
                $ntag = '<' . $matchs[1][$key] . $matchs[3][$key];
            }
            $content = str_replace($tag, $ntag, $content);
        }
        return $content;
    }

    /**
     * 优化HTML，清理多余空白字符
     * 
     * @Author nece001@163.com
     * @DateTime 2023-06-12
     * 
     * @param string $content
     * @return string
     */
    public static function optimize($content)
    {
        return preg_replace('/(?:[\s\r\n]+)?(<[^>]+?>)[\s\r\n]+/m', '${1}${2}', $content);
    }
}
