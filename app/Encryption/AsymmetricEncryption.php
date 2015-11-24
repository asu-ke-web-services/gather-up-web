<?php

interface AsymmetricEncryption
{
    public function createKeyPair();
    public function encryptMessage($plainText, $publicKey);
    public function decryptMessage($cipherText, $privateKey);
}
