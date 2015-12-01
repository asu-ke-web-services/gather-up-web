<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use GatherUp\Models\User;
use GatherUp\Models\TeamKey;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Http\Request;

class EncryptionController extends JsonController
{
    public function __construct(GateContract $gate)
    {
        $gate->define('publicKey', 'GatherUp\Policies\Api\VersionOne\TeamKeyPolicy@publicKey');
    }

    public function getPublicKey(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        $user = User::authToken($request->token)->first();
        $teamKey = TeamKey::authToken($request->token)->first();

        $this->authorizeForUser($user, 'publicKey', $teamKey);

        return response()->json(['public_key' => $teamKey->public_key]);
    }

    public function destroyPublicKey()
    {
        return response()->json(['success' => false]);
    }
}
