<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use Illuminate\Http\Request;

use GatherUp\Http\Requests;
use GatherUp\Http\Controllers\Controller;

class EventController extends JsonController
{
    public function store(Request $request)
    {
        $this->validate($request, [
            ''
        ]);
    }
}
