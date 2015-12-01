<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use GatherUp\Encryption\RsaEncryption;

/**
 * @group integration
 */
class EndToEndTest extends TestCase
{
    private $user;
    private $team;
    private $teamKey;
    private $signUpMessage1;
    private $signUpMessage2;

    public function setUp()
    {
        parent::setUp();

        $this->signUpMessage1 = '{
            "first_name": "Ivan",
            "last_name": "M",
            "email": "test@test.com",
            "birth_date": "2015-11-25"
        }';

        $this->signUpMessage2 = '{
            "first_name": "Ivan",
            "email": "test@test.com",
            "another_field": "Another answer"
        }';
    }

    public function insertRecords()
    {
        $this->user = factory(GatherUp\Models\User::class)->create([
            'name' => str_random(10),
            'email' => 'test@test.com',
            'password' => Hash::make('secret'),
        ]);
        $this->team = factory(GatherUp\Models\Team::class)->create([
            'name' => str_random(10),
            'owner_id' => $this->user->id,
        ]);
        $this->user->teams()->sync([$this->team->id]);
        $this->teamKey = factory(GatherUp\Models\TeamKey::class)->create([
            'team_id' => $this->team->id,
        ]);
    }

    public function testEndToEndApiEndpoints()
    {
        $this->insertRecords();

        // First step, sign in
        $this->post('/api/v1/sign_in', [
            'email' => 'test@test.com',
            'password' => 'secret',
            'team_id' => $this->team->id,
        ])->seeStatusCode(200);

        $token = json_decode($this->response->getContent())->token;

        // Second step, get public key
        $this->get('/api/v1/public_key?token='. $token)
             ->seeJson([
                 'public_key' => $this->teamKey->public_key
             ])
             ->seeStatusCode(200);

        $publicKey = json_decode($this->response->getContent())->public_key;

        // Third step, create an event
        $this->post('/api/v1/event', [
            'token' => $token,
            'title' => 'My Cool Event'
        ])->seeStatusCode(200);

        $eventId = json_decode($this->response->getContent())->event_id;

        // Fourth step, create sign ins
        $rsa = new RsaEncryption();
        $cipherSignUp1 = $rsa->encryptMessage($this->signUpMessage1, $publicKey);
        $cipherSignUp2 = $rsa->encryptMessage($this->signUpMessage2, $publicKey);

        $this->post('/api/v1/sign_up', [
            'token' => $token,
            'event_id' => $eventId,
            'cipher_sign_up' => $cipherSignUp1,
        ])->seeJson([
            'success' => true
        ])->seeStatusCode(200);

        $this->post('/api/v1/sign_up', [
            'token' => $token,
            'event_id' => $eventId,
            'cipher_sign_up' => $cipherSignUp2,
        ])->seeJson([
            'success' => true
        ])->seeStatusCode(200);
    }
}
