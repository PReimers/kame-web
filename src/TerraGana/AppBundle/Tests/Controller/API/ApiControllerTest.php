<?php

namespace TerraGana\AppBundle\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testUser()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('{"abc123":{"name":"user1"},"def456":{"name":"user2"}}', $client->getResponse()->getContent());
    }
}
