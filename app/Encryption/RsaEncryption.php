<?php

namespace GatherUp\Encryption;

use phpseclib\Crypt\RSA as CryptRsa;

class RsaEncryption implements AsymmetricEncryption
{
    private $rsa;

    public function __construct()
    {
        $this->rsa = new CryptRsa();
    }

    public function createKeyPair()
    {
        $keys = $this->rsa->createKey();

        return (object) array(
            'publicKey' => $keys['publickey'],
            'privateKey' => $keys['privatekey'],
        );
    }

    public function encryptMessage($plainText, $publicKey)
    {
        $this->rsa->loadKey($publicKey);

        return $this->rsa->encrypt($plainText);
    }

    public function decryptMessage($cipherText, $privateKey)
    {
        $this->rsa->loadKey($privateKey);

        return $this->rsa->decrypt($cipherText);
    }
}
