<?php

namespace GatherUp\Models;

use GatherUp\Models\User;
use GatherUp\Models\Team;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);     
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
