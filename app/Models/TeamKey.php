<?php

namespace GatherUp\Models;

use GatherUp\Models\Team;

use Illuminate\Database\Eloquent\Model;

class TeamKey extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Check for the existances of a AuthToken->Team->TeamKey
     * relationship between an active public key for the team
     * and a given $token
     */
    public function scopeAuthToken($query, $token)
    {
        return $query->whereHas('team', function ($query) use ($token)
        {
            $query->whereHas('authTokens', function ($innerQuery) use ($token)
            {
                $innerQuery->whereToken($token);
            });
        });
    }
}
