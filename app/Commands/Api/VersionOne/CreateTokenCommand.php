<?php

namespace GatherUp\Commands\Api\VersionOne;

use GatherUp\Models\AuthToken;
use GatherUp\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateTokenCommand extends Command implements SelfHandling
{
    private $userId;
    private $teamId;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($userId, $teamId)
    {
        $this->userId = $userId;
        $this->teamId = $teamId;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $authToken = AuthToken::create([
            'user_id' => $this->userId,
            'team_id' => $this->teamId,
            'token' => uniqid(),
        ]);

        return $authToken->token;
    }
}
