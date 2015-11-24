<?php

namespace GatherUp\Policies\Api\VersionOne;

use Illuminate\Auth\Access\HandlesAuthorization;

class EncryptionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * The given AuthToken has access to the given TeamKey if the $token's
     * team_id is the $teamKey's team_id and neither one is deactivated.
     * 
     * It then must also 
     */
    public function publicKey(TeamKey $teamKey) {
        return $teamKey !== null;
    }
}
