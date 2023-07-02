<?php

namespace Nece\Util;

/**
 * 密码相关工具
 *
 * @Author nece001@163.com
 * @DateTime 2023-07-02
 */
class PasswordUtil
{
    /**
     * 密码加密
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $text 明文
     *
     * @return string 密文(60 个字符的字符串)
     */
    public static function hash($text)
    {
        return password_hash($text, PASSWORD_BCRYPT);
    }

    /**
     * 验证密码是否正确
     *
     * @Author nece001@163.com
     * @DateTime 2023-07-02
     *
     * @param string $password 明文
     * @param string $hash 密文
     *
     * @return bool
     */
    public static function verify($text, $hash)
    {
        return password_verify($text,  $hash);
    }
}
