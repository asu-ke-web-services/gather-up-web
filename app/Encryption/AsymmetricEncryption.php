<?php

namespace GatherUp\Encryption;

/**
 * Not versioned by API number because the encryption algorithms
 * should not change
 */
interface AsymmetricEncryption
{
    /**
     * Create a public and private key
     * 
     * @return Object
     */
    public function createKeyPair();

    /**
     * Encrypt the given $plainText with the given $publicKey
     *
     * @return String
     */
    public function encryptMessage($plainText, $publicKey);

    /**
     * Decrypt the given $cipherText with the given $privateKey
     *
     * @return String
     */
    public function decryptMessage($cipherText, $privateKey);
}
