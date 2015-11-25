<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use GatherUp\Encryption\RsaEncryption;

class SignUpControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $authToken;
    private $team;
    private $teamKey;
    private $event;

    public function setUp()
    {
        parent::setUp();
    }

    public function insertRecords()
    {
        $this->user = factory(GatherUp\Models\User::class)->create([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => 'secret',
        ]);
        $this->team = factory(GatherUp\Models\Team::class)->create([
            'name' => str_random(10),
            'owner_id' => $this->user->id,
        ]);
        $this->teamKey = factory(GatherUp\Models\TeamKey::class)->create([
            'team_id' => $this->team->id,
        ]);
        $this->authToken = factory(GatherUp\Models\AuthToken::class)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'token' => 'test',
        ]);
        $this->event = factory(GatherUp\Models\Event::class)->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
    }

    public function testCanStoreSignUp()
    {
        $this->insertRecords();

        $rsa = new RsaEncryption();
        $publicKey = $this->teamKey->public_key;
        $message = '{ "sign": "up" }';

        $cipherSignUp = $rsa->encryptMessage($message, $publicKey);

        $response = $this->post('/api/v1/sign_up', [
            'token' => 'test',
            'event_id' => $this->event->id,
            'cipher_sign_up' => $cipherSignUp,
        ])->seeJson([
            'success' => true
        ])->seeStatusCode(200);

        // TODO check to make sure the data is in the database
    }

    public function testCannotStoreSignUpBecauseOfBadRequest()
    {
        $this->insertRecords();

        $response = $this->post('/api/v1/sign_up', [
            'token' => 'test',
            'event_id' => $this->event->id,
            'cipher_sign_up' => 'bad text',
        ])->seeJson([
            'success' => false
        ])->seeStatusCode(400);
    }
}
