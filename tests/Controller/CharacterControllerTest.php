<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
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
        $this->client->request(
            'POST',
            '/character/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg", "player": 1}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 200);
        $this->assertIdentifier();
        $this->defineIdentifier();
    }

    public function testDisplay()
    {
        $this->client->request('GET', '/character/display/' . self::$identifier);

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

    }

    /**
     * Test bad identifier
     */
    public function testBadIdentifier(){
        $client = static ::createClient();
        $client->request('GET', '/character/display/8f74f20597c5cf99dd42cd31331b7e6e2arre');

        $response = $client->getResponse();
        $this->assertBadJsonResponse($response);
    }

    /**
     * Test To redirect to index
     */
    public function testRedirectionIndex()
    {
        $this->client->request('GET', '/character');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test display all
     */
    public function testIndex()
    {
        $this->client->request('GET', '/character/index');

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

    }

    /**
     * Test display all characters by intelligence
     */
    public function testIndexIntelligence()
    {
        $this->client->request('GET', '/character/index/50');

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
        $this->client->request(
            'PUT',
            '/character/modify/' . self::$identifier,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"kind":"Seigneur", "name":"Gorthol"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 200);
        $this->assertIdentifier();

        $this->client->request(
            'PUT',
            '/character/modify/' . self::$identifier,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 200);
        $this->assertIdentifier();


    }

    /**
     * Tests delete
     */
    public function testDelete()
    {
        $this->client->request('DELETE', '/character/delete/' . self::$identifier);
        $this->assertJsonResponse($this->client->getResponse(), 200);
    }
}
