<?php

namespace TerraGana\AppBundle\Tests\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/backend/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Backend', $crawler->text());
    }
}
