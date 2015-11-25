<?php

namespace GatherUp\Policies\Api\VersionOne;

use GatherUp\Models\User;
use GatherUp\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthTokenPolicy
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

    public function token(User $user, Team $team)
    {
        return $user->belongsToTeam($team);
    }
}
