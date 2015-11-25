<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use GatherUp\Models\User;
use GatherUp\Models\TeamKey;
use GatherUp\Commands\Api\VersionOne\StoreSignUpCommand;

class SignUpController extends JsonController
{
    use DispatchesJobs;

    public function __construct(GateContract $gate)
    {
        $gate->define('publicKey', 'GatherUp\Policies\Api\VersionOne\TeamKeyPolicy@publicKey');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'cipher_sign_up' => 'required',
        ]);

        $user = User::authToken($request->token)->first();
        $teamKey = TeamKey::authToken($request->token)->first();

        // Verify that the given user has access to the public key
        $this->authorizeForUser($user, 'publicKey', $teamKey);

        $successfullyDecrypted = $this->dispatch(
            new StoreSignUpCommand(
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
