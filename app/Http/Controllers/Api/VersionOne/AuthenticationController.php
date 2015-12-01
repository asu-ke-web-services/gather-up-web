<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use Auth;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

use GatherUp\Models\User;
use GatherUp\Models\Team;
use GatherUp\Http\Requests;
use GatherUp\Http\Controllers\Controller;
use GatherUp\Commands\Api\VersionOne\CreateTokenCommand;

class AuthenticationController extends JsonController
{
    use DispatchesJobs;

    public function __construct(GateContract $gate)
    {
        $gate->define('token', 'GatherUp\Policies\Api\VersionOne\AuthTokenPolicy@token');
    }

    public function getToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'team_id' => 'required|integer',
        ]);

        $authenticated = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($authenticated)
        {
            $team = Team::find($request->team_id);
            $user = User::where('email', $request->email)->first();

            $this->authorizeForUser($user, 'token', $team);

            $token = $this->dispatch(
                new CreateTokenCommand(
                    $user->id,
                    $team->id
                )
            );

            return response()->json(['token' => $token]);
        }

        return response()->json([
            'authorization' => 'This action is unauthorized.',
        ], 403);
    }
}
