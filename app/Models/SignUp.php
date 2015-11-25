<?php

namespace GatherUp\Models;

use Illuminate\Database\Eloquent\Model;

class SignUp extends Model
{
    /**
     * The attributes that are blacklisted from being mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
