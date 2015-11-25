<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use GatherUp\Http\Controllers\Controller;

class DocumentationController extends Controller
{
  public function get() {
    return response()->json([
        'name' => 'Gather Up API',
        'version' => '1',
        'created_date' => '11-25-2015',
        'description' => 'The Gather Up API provides endpoints for securely storing event sign ups',
        'endpoints' => [
            'sign_in' => [
                'method' => 'POST',
                'arguments' => [
                    'email' => 'String',
                    'password' => 'String',
                    'team_id' => 'Integer',
                ],
                'returns' => [
                    'token' => 'String',
                ],
            ],
            'sign_out' => [
                'method' => 'POST',
                'arguments' => [
                    'token' => 'String',
                ],
                'returns' => [
                    'success' => 'Boolean',
                ],
            ],
            'public_key' => [
                'method' => 'GET',
                'arguments' => [
                    'token' => 'String',
                ],
                'returns' => [
                    'public_key' => 'String',
                ],
            ],
            'event' => [
                'method' => 'POST',
                'arguments' => [
                    'token' => 'String',
                    ''
                ],
                'returns' => [
                    'event_id' => 'Integer',
                ],
            ],
            'sign_up' => [
                'method' => 'POST',
                'arguments' => [
                    'token'=> 'String',
                    'event_id' => 'Integer',
                    'cipher_sign_up' => [
                        'type' => 'String',
                        'description' => 'RSA encrypted json blob with a first_name, last_name, email, and birth_date',
                    ],
                ],
                'returns' => [
                    'success' => 'Boolean',
                ],
            ],
        ]
    ]);
  }
}
