<?php

namespace TerraGana\AppBundle\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testUser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Just do it', $crawler->text());
    }
}
