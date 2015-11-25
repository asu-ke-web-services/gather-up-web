<?php

namespace GatherUp\Models;

use Illuminate\Database\Eloquent\Model;

class SignUps extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
