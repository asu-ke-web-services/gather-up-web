<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

use GatherUp\Models\Event;
use GatherUp\Models\TeamKey;
use GatherUp\Models\User;
use GatherUp\Commands\Api\VersionOne\StoreSignUpCommand;

class SignUpController extends JsonController
{
    use DispatchesJobs;

    public function __construct(GateContract $gate)
    {
        $gate->define('publicKey', 'GatherUp\Policies\Api\VersionOne\TeamKeyPolicy@publicKey');
        $gate->define('event', 'GatherUp\Policies\Api\VersionOne\EventPolicy@event');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'event_id' => 'required|integer',
            'cipher_sign_up' => 'required',
        ]);

        $user = User::authToken($request->token)->first();
        $teamKey = TeamKey::authToken($request->token)->first();
        $event = Event::find($request->event_id);

        // Verify that the given user has access to the public key
        $this->authorizeForUser($user, 'publicKey', $teamKey);
        // Verify the given user has access to the team event combo
        $this->authorizeForUser($user, 'event', [ $event, $teamKey ]);

        $successfullyDecrypted = $this->dispatch(
            new StoreSignUpCommand(
                $request->event_id,
                $request->cipher_sign_up,
                $teamKey->private_key
            )
        );

        if ($successfullyDecrypted)
        {
            return response()->json(['success' => true]);
        }
        else
        {
            return response()->json(['success' => false], 400);    
        }
    }
}
