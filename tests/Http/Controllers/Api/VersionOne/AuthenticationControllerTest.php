<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $team;

    public function setUp()
    {
        parent::setUp();
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

    public function testCanSignIn()
    {
        $this->insertRecords();
        $response = $this->post('/api/v1/sign_in', [
            'email' => 'test@test.com',
            'password' => 'secret',
            'team_id' => $this->team->id,
        ]);

        $content = $this->response->getContent();
        $this->assertRegExp('/"token"(\s)?:(\s)?"(.*)"/i', $content);

        $response->seeStatusCode(200);
    }

    public function testCannotSignIn()
    {
        $this->insertRecords();
        $response = $this->post('/api/v1/sign_in', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword',
            'team_id' => $this->team->id,
        ]);

        $response->seeJson([
            'authorization' => 'This action is unauthorized.',
        ])
        ->seeStatusCode(403); 
    }
}
