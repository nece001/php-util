<?php

namespace Nece\Util\Encryption\Des;

/**
 * 非对称加密
 *
 * @Author nece001@163.com
 * @DateTime 2023-10-30
 */
class OpenSSL
{
    /**
     * 生成密钥文件
     *
     * @Author nece001@163.com
     * @DateTime 2023-10-30
     *
     * @param string $config 配置文件，在PHP目录下即可找到：php-7.2.8-nts-Win32-VC15-x64\extras\ssl\openssl.cnf
     * @param string $private_key_file 生成的私钥文件路径
     * @param string $public_key_file 生成的公钥文件路径
     * @param string $passphrase 密码，默认无密码
     *
     * @return void
     */
    public function createkeyFile($config, $private_key_file, $public_key_file, $passphrase = null)
    {
        $options = array(
            "digest_alg" => "sha512", // 摘要算法,可通过函数获取:openssl_get_md_methods()
            "private_key_bits" => 1024, //字节数    512 1024  2048   4096 等
            "private_key_type" => OPENSSL_KEYTYPE_RSA, //加密类型
            "config" => $config
        );

        $res = openssl_pkey_new($options);
        openssl_pkey_export($res, $priKey, $passphrase, $options); // 生成私钥
        $result = openssl_pkey_get_details($res); // 生成公钥
        $pubKey = $result["key"]; // 公钥内容

        file_put_contents($private_key_file, $priKey);
        file_put_contents($public_key_file, $pubKey);
    }

    /**
     * 私钥加密
     *
     * @author nece001@163.com
     * @created 2022-07-19 15:56:46
     *
     * @param string $text 明文
     * @param string $private_key_file 私钥文件路径
     * @return string 密文(base64)
     */
    public function privateKeyEncode($text, $private_key_file)
    {
        $priKey = $this->getKeyContent($private_key_file);
        openssl_private_encrypt($text, $encrypted, $priKey);
        return base64_encode($encrypted);
    }

    /**
     * 私钥解密
     *
     * @author nece001@163.com
     * @created 2022-07-19 15:58:11
     *
     * @param string $ciphertext 密文(base64)
     * @param string $private_key_file 私钥文件路径
     * @return string 明文
     */
    public function privateKeyDecode($ciphertext, $private_key_file)
    {
        $priKey = $this->getKeyContent($private_key_file);
        $encrypted = base64_decode($ciphertext);
        openssl_private_decrypt($encrypted, $decrypted, $priKey);
        return $decrypted;
    }

    /**
     * 公钥加密
     *
     * @author nece001@163.com
     * @created 2022-07-19 16:03:11
     *
     * @param string $text 明文
     * @param string $public_key_file 公钥文件路径
     * @return string 密文(base64)
     */
    public function publicKeyEncode($text, $public_key_file)
    {
        $pubKey = $this->getKeyContent($public_key_file);
        openssl_public_encrypt($text, $encrypted, $pubKey);
        return base64_encode($encrypted);
    }

    /**
     * 公钥解密
     *
     * @author nece001@163.com
     * @created 2022-07-19 16:03:26
     *
     * @param string $ciphertext 密文(base64)
     * @param string $public_key_file 公钥文件路径
     * @return string 明文
     */
    public function publicKeyDecode($ciphertext, $public_key_file)
    {
        $pubKey = $this->getKeyContent($public_key_file);
        $encrypted = base64_decode($ciphertext);
        openssl_public_decrypt($encrypted, $decrypted, $pubKey);
        return $decrypted;
    }

    /**
     * 读取密钥文件内容
     *
     * @author nece001@163.com
     * @created 2022-07-19 16:04:43
     *
     * @param string $filename 文件路径
     * @return string
     */
    private function getKeyContent($filename)
    {
        static $content = array();
        if (!isset($content[$filename])) {
            $content[$filename] = file_get_contents($filename);
        }
        return $content[$filename];
    }
}
