<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

use GatherUp\Models\TeamKey;
use GatherUp\Models\User;
use GatherUp\Commands\Api\VersionOne\StoreEventCommand;

class EventController extends JsonController
{
    use DispatchesJobs;

    public function __construct(GateContract $gate)
    {
        $gate->define('publicKey', 'GatherUp\Policies\Api\VersionOne\TeamKeyPolicy@publicKey');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'title' => 'required'
        ]);

        $user = User::authToken($request->token)->first();
        $teamKey = TeamKey::authToken($request->token)->first();

        // Verify that the given user has access to the public key
        $this->authorizeForUser($user, 'publicKey', $teamKey);

        $team = $teamKey->team()->first();

        $eventId = $this->dispatch(
            new StoreEventCommand(
                $request,
                $user->id,
                $team->id
            )
        );

        return response()->json(['event_id' => $eventId]);
    }
}
