<?php

namespace GatherUp\Policies\Api\VersionOne;

use GatherUp\Models\TeamKey;
use GatherUp\Models\User;

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
     * At this point, if we have a team key, then they have
     * access to the public key of that team
     */
    public function publicKey(User $user, TeamKey $teamKey) {
        return $user !== null && $teamKey !== null;
    }

    /**
     * No one ever has access to the private key
     */
    public function privateKey() {
        return false;
    }
}
