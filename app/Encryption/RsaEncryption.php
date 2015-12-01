<?php

namespace GatherUp\Encryption;

use phpseclib\Crypt\RSA as CryptRsa;

/**
 * Uses phpseclib RSA encryption algorithm for generating public/private
 * keys, encrypting and decrypting messages
 *
 * @implements AsymmetricEncryption
 */
class RsaEncryption implements AsymmetricEncryption
{
    /**
     * @type CryptRsa
     */
    private $rsa;

    /**
     * Create a RsaEncryption object for creating and using
     * public and private keys
     *
     * @constructor
     */
    public function __construct()
    {
        $this->rsa = new CryptRsa();
    }

    /**
     * Generate an object with a publicKey and privateKey
     *
     * @override
     * @return Object
     */
    public function createKeyPair()
    {
        $keys = $this->rsa->createKey();

        return (object) array(
            'publicKey' => $keys['publickey'],
            'privateKey' => $keys['privatekey'],
        );
    }

    /**
     * Encrypt the given $plainText using the RSA algorithm
     *
     * @param $plainText String
     * @param $publicKey String
     * @return String
     */
    public function encryptMessage($plainText, $publicKey)
    {
        $this->rsa->loadKey($publicKey);

        return $this->rsa->encrypt($plainText);
    }

    /**
     * Decrypt the given $cipherTExt using the RSA algorithm
     *
     * @param $cipherText String
     * @param $privateKey String
     * @return String
     */
    public function decryptMessage($cipherText, $privateKey)
    {
        $this->rsa->loadKey($privateKey);

        return $this->rsa->decrypt($cipherText);
    }
}
