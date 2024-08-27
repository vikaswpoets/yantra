<?php

namespace System\Utilities;
class EncryptionUtil
{
    private const METHOD = 'AES-256-CBC';
    private const SECRET_KEY = 'Aarav'; // Use a secure key

    public static function encrypt($data): string
    {
        $key = hash('sha256', self::SECRET_KEY, true);
        $iv = openssl_random_pseudo_bytes(16); // Generate a random IV
        $encryptedData = openssl_encrypt($data, self::METHOD, $key, 0, $iv);
        return base64_encode($iv . $encryptedData); // Concatenate IV and encrypted data
    }

    public static function decrypt($data): string
    {
        $key = hash('sha256', self::SECRET_KEY, true);
        $data = base64_decode($data);
        $iv = substr($data, 0, 16); // Extract IV
        $encryptedData = substr($data, 16); // Extract encrypted data
        return openssl_decrypt($encryptedData, self::METHOD, $key, 0, $iv);
    }
}

