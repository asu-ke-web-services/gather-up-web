<?php

namespace GatherUp\Validation;

class JsonValidation
{
    public function isValid($json)
    {
        return is_string($json) &&
               is_array(json_decode($json, true)) &&
               (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}