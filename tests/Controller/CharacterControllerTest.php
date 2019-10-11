<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static ::createClient();
        $client->request('GET', '/character/display');

        $response = $client->getResponse();
        $this->assertJsonResponse($response);

    }

    public function testIndex()
    {
        $client = static ::createClient();
        $client->request('GET', '/character/display');

        $response = $client->getResponse();
        $this->assertJsonResponse($response);

    }

    public function assertJsonResponse($response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }
}
