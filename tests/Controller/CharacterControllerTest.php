<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{

    private $client;

    public function setUp(){
        $this->client = static::createClient();
    }

    public function testDisplay()
    {
        $this->client->request('GET', '/character/display/8f74f20597c5cf99dd42cd31331b7e6e2ae1fb45');

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

    }

//    public function testBadIdentifier(){
//        $client = static ::createClient();
//        $client->request('GET', '/character/display/8f74f20597c5cf99dd42cd31331b7e6e2arre');
//
//        $response = $client->getResponse();
//        $this->assertBadJsonResponse($response);
//    }

    public function testRedirectionIndex()
    {
        $this->client->request('GET', '/character');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
        $this->client->request('GET', '/character/index');

        $response = $this->client->getResponse();
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
