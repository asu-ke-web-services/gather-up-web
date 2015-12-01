<?php

namespace GatherUp\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are blacklisted from being mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function signUps()
    {
        return $this->hasMany(SignUp::class);
    }
}
