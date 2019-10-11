<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static ::createClient();
        $client->request('GET', '/character/display/8f74f20597c5cf99dd42cd31331b7e6e2ae1fb45');

        $response = $client->getResponse();
        $this->assertJsonResponse($response);

    }

    public function testBadIdentifier(){
        $client = static ::createClient();
        $client->request('GET', '/character/display/8f74f20597c5cf99dd42cd31331b7e6e2arre');

        $response = $client->getResponse();
        $this->assertBadJsonResponse($response);
    }

    public function testIndex()
    {
        $client = static ::createClient();
        $client->request('GET', '/character');

        $response = $client->getResponse();
        $this->assertJsonResponse($response);

    }

    public function assertJsonResponse($response )
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    public function assertBadJsonResponse($response)
    {
        $this->assertEquals(404, $response->getStatusCode());
    }
}
