<?php

namespace GatherUp\Policies\Api\VersionOne;

use GatherUp\Models\TeamKey;
use GatherUp\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TeamKeyPolicy
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
     * One a user on the given team for the given team key
     * should be able to see the public key
     */
    public function publicKey(User $user, TeamKey $teamKey) {
        return $user !== null && $teamKey !== null;
    }
}
