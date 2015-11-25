<?php

namespace GatherUp\Commands\Api\VersionOne;

use GatherUp\Encryption\RsaEncryption;
use GatherUp\Validation\JsonValidation;
use GatherUp\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;

class StoreSignUpCommand extends Command implements SelfHandling
{
    private $eventId;
    private $cipherText;
    private $privateKey;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($eventId, $cipherText, $privateKey)
    {
        $this->eventId = $eventId;
        $this->cipherText = $cipherText;
        $this->privateKey = $privateKey;
    }

    /**
     * Execute the command.
     *
     * @return Boolean
     */
    public function handle(RsaEncryption $rsa, JsonValidation $jsonValidation)
    {
        try
        {
            $json = $rsa->decryptMessage($this->cipherText, $this->privateKey);
        }
        catch (\ErrorException $e)
        {
            if ($e->getMessage() === 'Decryption error')
            {
                return false;
            }
        } 

        if ($jsonValidation->isValid($json))
        {
            // Store it
            var_dump( $json );

            return true;
        }
        else
        {
            return false;
        }
    }
}
