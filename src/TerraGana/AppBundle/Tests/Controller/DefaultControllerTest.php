<?php

namespace TerraGana\AppBundle\Tests\Controller;

use TerraGana\AppBundle\Tests\PrepareWebTestCase;

class DefaultControllerTest extends PrepareWebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('This website is still under construction.', $crawler->filter('.alert.alert-info')->text());
    }
}
