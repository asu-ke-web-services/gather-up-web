<?php

use GatherUp\Models\User;
use GatherUp\Models\Team;
use GatherUp\Models\AuthToken;
use GatherUp\Models\TeamKey;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('team_keys')->delete();
        DB::table('auth_tokens')->delete();
        DB::table('teams')->delete();
        DB::table('users')->delete();

        factory(User::class, 10)->create()->each(function($u)
        {
            $team = $u->teams()->save(factory(Team::class, 1)->create());
            
            $team->authTokens()->save(factory(AuthToken::class, 1)->make([
                'user_id' => $u->id
            ]));

            $team->teamKeys()->save(factory(TeamKey::class, 1)->make());
        });
    }
}
