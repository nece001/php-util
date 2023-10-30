<?php

namespace Nece\Util\Encryption\Des;

/**
 * 对称加密抽象类
 *
 * @Author nece001@163.com
 * @DateTime 2023-10-30
 */
abstract class DesAbstract
{
    /**
     * 加密
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $text
     *
     * @return string
     */
    abstract public function encrypt($text);

    /**
     * 解密
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $ciphertext
     *
     * @return string
     */
    abstract public function decrypt($ciphertext);

    /**
     * 十六进制转二进制
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $hexData
     *
     * @return string
     */
    public function hex2bin($hexData)
    {
        $binData = '';
        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }

    /**
     * pkcs5/pkcs7 补位
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $text
     * @param string $blocksize
     *
     * @return string
     */
    public function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * pkcs5/pkcs7 去除补位
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $text
     *
     * @return string
     */
    public function pkcs5Unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) {
            return false;
        }

        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }

        return substr($text, 0, -1 * $pad);
    }
}
