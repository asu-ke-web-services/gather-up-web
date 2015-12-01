<?php

namespace GatherUp\Validation;

use Auth;
use Validator;
use GatherUp\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamValidation
{
    use HandlesAuthorization;

    const FREE_TEAMS = 0;
    const BASIC_TEAMS = 1;
    const PREMIUM_TEAMS = 10;
    const ERROR_MESSAGE = 'You have reached the maximum number of teams for your plan';

    /**
     * Create a new policy instance. Will create a new validator.
     *
     * @return void
     */
    public function __construct()
    {
        Validator::extend('canMakeTeam', 'GatherUp\Policies\TeamValidation@canMakeTeam', self::ERROR_MESSAGE);
    }

    /**
     * Returns an array. By convention, the key is the value that
     * will be checked, in this case, we will not actually be checking
     * the "name" key of the given request object. We only require that
     * it exists. This is sort of hacky. We can make it so that
     * the validator is implicit (in the constructor) and will also be run,
     * or we can just require name to be set and then have the `canMakeTeam`
     * validator run.
     * 
     * @return array
     */
    public function validateNewTeamsWith()
    {
        return [
            'name' => 'canMakeTeam'
        ];
    }

    /**
     * Will check if the given user can make a team, this
     * is based on a few rules:
     *
     * 1. Whether the plan is active
     * 2. The plan type
     * 
     * @return Boolean Whether the user can make a team
     */
    public function canMakeTeam()
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
