<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EncryptionControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $authToken;
    private $team;
    private $teamKey;

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
    }

    public function testCanAccessPublicKey()
    {
        $this->insertRecords();
        $this->get('/api/v1/public_key?token='. $this->authToken->token)
             ->seeJson([
                 'public_key' => $this->teamKey->public_key
             ])
             ->seeStatusCode(200);
    }

    public function testCannotAccessPublicKey()
    {
        $this->insertRecords();
        $this->get('/api/v1/public_key?token=someinvalidkey')
            ->seeJson([
                'authorization' => 'This action is unauthorized.'
            ])
            ->seeStatusCode(403);
    }
}
