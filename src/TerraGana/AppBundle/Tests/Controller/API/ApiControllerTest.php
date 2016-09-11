<?php

namespace TerraGana\AppBundle\Tests\Controller\API;

use Symfony\Component\HttpFoundation\Response;
use TerraGana\AppBundle\Tests\PrepareWebTestCase;

class ApiControllerTest extends PrepareWebTestCase
{
    public function testGames()
    {
        $client = static::createClient();

        $client->request('GET', '/api/games');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('{"demo":true}', $client->getResponse()->getContent());
    }

    public function testGamesKuaine()
    {
        $client = static::createClient();

        $client->request('GET', '/api/games/kuaine');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('{"demo":true}', $client->getResponse()->getContent());
    }
}
