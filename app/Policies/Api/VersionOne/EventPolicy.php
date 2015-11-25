<?php

namespace GatherUp\Policies\Api\VersionOne;

use GatherUp\Models\Event;
use GatherUp\Models\TeamKey;
use GatherUp\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
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
     * If the event and the team key both belong to the same
     * team, then the user has the ability to fiddle
     * with that event.
     */
    public function event(User $user, Event $event, TeamKey $teamKey) {
        if ($user !== null && $teamKey !== null && $event !== null)
        {
            $teamKeyTeam = $teamKey->team()->first();
            $eventTeam = $event->team()->first();

            return $teamKeyTeam->id === $eventTeam->id;
        }

        return false;
    }
}
