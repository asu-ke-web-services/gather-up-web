<?php

namespace GatherUp\Models;

use GatherUp\Models\TeamKey;
use GatherUp\Models\AuthToken;

use Laravel\Spark\Teams\Team as SparkTeam;

class Team extends SparkTeam
{
    public function teamKeys()
    {
        return $this->hasMany(TeamKey::class);
    }

    public function authTokens()
    {
        return $this->hasMany(AuthToken::class);
    }
}
