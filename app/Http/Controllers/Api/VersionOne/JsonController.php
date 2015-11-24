<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use GatherUp\Http\Controllers\Controller;

class JsonController extends Controller
{
    /**
     * Return a json response if the validation fails
     * 
     * @override
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return response()->json(['error' => $errors]);
    }

    /**
     * Return a json response if the authorization fails
     *
     * @override
     */
    protected function createGateUnauthorizedException($ability, $arguments, $message = 'This action is unauthorized.', $previousException = null)
    {
        return new HttpException(
            403,
            json_encode(['error' => ['authorization' => $message]]),
            $previousException
        );
    }
}
