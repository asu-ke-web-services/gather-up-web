<?php

namespace GatherUp\Policies;

use Auth;
use Validator;
use GatherUp\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    const FREE_TEAMS = 0;
    const BASIC_TEAMS = 1;
    const PREMIUM_TEAMS = 10;
    const ERROR_MESSAGE = 'You have reached the maximum number of teams for your plan';

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        Validator::extend('canMakeTeam', 'GatherUp\Policies\TeamPolicy@canMakeTeam', self::ERROR_MESSAGE);
    }

    public function validateNewTeamsWith()
    {
        return [
            'name' => 'canMakeTeam'
        ];
    }

    public function canMakeTeam($attribute, $userId, $parameters, $validator)
    {
        $user = User::findOrFail(Auth::id());
        $numberOfTeams = $user->getNumberOfTeams();

        if ($user->planIsActive()) {
            $planType = $user->getPlanType();

            return $this->hasLessTeamsThanTheMaximum($planType, $numberOfTeams);
        }
        return false;
    }

    private function hasLessTeamsThanTheMaximum($planType, $numberOfTeams)
    {
        switch($planType) {
            case config('services.stripe.plans.basic_monthly_plan_id'):
                return $numberOfTeams < self::BASIC_TEAMS;
            default:
                return $numberOfTeams < self::FREE_TEAMS;
        }
    }
}
