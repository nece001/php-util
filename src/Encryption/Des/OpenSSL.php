<?php

namespace Nece\Util\Encryption\Des;

/**
 * 使用openSSL方法
 *
 * @Author nece001@163.com
 * @DateTime 2023-10-30
 */
class OpenSSL extends DesAbstract
{
    protected $method;
    protected $key;
    protected $iv;

    /**
     * 构造
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $key 密钥
     * @param string $iv 偏移量
     * @param string $method 加密算法名
     */
    public function __construct($key, $iv = '', $method = 'aes-128-cbc')
    {
        $this->key = $key;
        $this->method = $method;
        if (empty($iv) && openssl_cipher_iv_length($method)) {
            $this->iv = $key; // 需要iv的算法，默认以$key 作为 iv
        } else {
            $this->iv = $iv;
        }
    }

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
    public function encrypt($text)
    {
        $data = $this->pkcs5Pad($text, strlen($this->key));
        return base64_encode(openssl_encrypt($data, $this->method, $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv));
    }

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
    public function decrypt($ciphertext)
    {
        return $this->pkcs5Unpad(openssl_decrypt(base64_decode($ciphertext), $this->method, $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv));
    }
}
