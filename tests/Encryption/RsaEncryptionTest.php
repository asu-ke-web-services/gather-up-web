<?php

use GatherUp\Encryption\RsaEncryption;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RsaEncryptionTest extends TestCase
{
    protected $rsa;

    public function setUp()
    {
        $this->rsa = new RsaEncryption();
    }

    public function testCanGenerateKeyPairs()
    {
        $keyPair = $this->rsa->createKeyPair();

        $this->assertNotEmpty($keyPair->publicKey);
        $this->assertNotEmpty($keyPair->privateKey);
    }

    public function testCanEncryptAndDecrypyMessage()
    {
        $keyPair = $this->rsa->createKeyPair();

        $message = 'Good dog';

        $cipherText = $this->rsa->encryptMessage(
            $message,
            $keyPair->publicKey
        );

        $plainText = $this->rsa->decryptMessage(
            $cipherText,
            $keyPair->privateKey
        );

        $this->assertNotEquals($cipherText, $message);
        $this->assertEquals($message, $plainText);
    }
}
