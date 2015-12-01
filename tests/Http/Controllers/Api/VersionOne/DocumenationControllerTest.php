<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumenationControllerTest extends TestCase
{
    public function testReturnsVersionNumber()
    {
        $this->get('/api/v1')->seeJson([
            'version' => '1'
        ])->seeStatusCode(200);
    }
}
