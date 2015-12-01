<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventControllerTest extends TestCase
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

    public function testCanStoreEvent()
    {
        $this->insertRecords();

        $response = $this->post('/api/v1/event', [
            'token' => 'test',
            'title' => 'My Cool Event'
        ]);

        $content = $this->response->getContent();
        $this->assertRegExp('/"event_id"(\s)?:(\s)?((\d+)*)/i', $content);

        $response->seeStatusCode(200);
    }

    public function testCannotStoreSignUpBecauseUnauthorized()
    {
        $this->insertRecords();

        $response = $this->post('/api/v1/event', [
            'token' => 'incorrect token',
            'title' => 'My Cool Event'
        ])->seeJson([
            'authorization' => 'This action is unauthorized.'
        ])->seeStatusCode(403);
    }
}
