<?php

namespace GatherUp\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function signUps()
    {
        return $this->hasMany(SignUp::class);
    }
}
