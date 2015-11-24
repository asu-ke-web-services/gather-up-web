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
}
