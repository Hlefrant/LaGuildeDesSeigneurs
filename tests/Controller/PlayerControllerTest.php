<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{

    private $client;
    private $content;
    private static $identifier;

    public function setUp(){
        $this->client = static::createClient();
    }

    public function assertJsonResponse($response, int $statusCode = 200)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);

        $this->content = json_decode($response->getContent(),true, 50);
    }

    /**
     * Asserts that 'identifier' is present in the Response
     */
    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier',$this->content);
    }

    /**
     * Defines identifier
     */
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }

    public function testCreate()
    {
        $this->client->request('POST', '/player/create');
        $this->assertJsonResponse($this->client->getResponse(), 200);

        $this->assertIdentifier();
        $this->defineIdentifier();
    }

    public function testDisplay()
    {
        $this->client->request('GET', '/player/display/' . self::$identifier);

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

    }

    public function testBadIdentifier(){
        $client = static ::createClient();
        $client->request('GET', '/player/display/8f74f20597c5cf99dd42cd31331b7e6e2arre');

        $response = $client->getResponse();
        $this->assertBadJsonResponse($response);
    }

    public function testRedirectionIndex()
    {
        $this->client->request('GET', '/player');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
        $this->client->request('GET', '/player/index');

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

    }


    public function assertBadJsonResponse($response)
    {
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Tests modify
     */
    public function testModify()
    {
        $this->client->request('PUT', '/player/modify/' . self::$identifier);
        $this->assertJsonResponse($this->client->getResponse(), 200);
    }

    /**
     * Tests delete
     */
    public function testDelete()
    {
        $this->client->request('DELETE', '/player/delete/' . self::$identifier);
        $this->assertJsonResponse($this->client->getResponse(), 200);
    }
}
