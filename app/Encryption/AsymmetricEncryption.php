<?php

namespace GatherUp\Encryption;

/**
 * Not versioned by API number because the encryption algorithms
 * should not change
 */
interface AsymmetricEncryption
{
    public function createKeyPair();
    public function encryptMessage($plainText, $publicKey);
    public function decryptMessage($cipherText, $privateKey);
}
