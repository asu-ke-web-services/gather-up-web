<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use GatherUp\Models\TeamKey;

use Illuminate\Http\Request;

class EncryptionController extends JsonController
{
    public function getPublicKey(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $teamKey = TeamKey::whereHas('team', function ($query) use ($request)
        {
            $query->whereHas('authTokens', function ($innerQuery) use ($request)
            {
                $innerQuery->whereToken($request->token);
            });
        })->first();

        $this->authorize('publicKey', $teamKey);

        return response()->json(['public_key' => $teamKey->publicKey]);
    }

    public function destroyPublicKey()
    {
        return response()->json(['success' => true]);
    }
}
