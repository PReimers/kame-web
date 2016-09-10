<?php

namespace TerraGana\AppBundle\Tests\Controller\Backend;

use TerraGana\AppBundle\Tests\PrepareWebTestCase;

class BackendControllerTest extends PrepareWebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/backend/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Backend', $crawler->text());
    }
}
