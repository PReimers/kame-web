<?php

namespace TerraGana\AppBundle\Tests\Helper;

use TerraGana\AppBundle\Helper\JsonHelper;
use TerraGana\AppBundle\Tests\PrepareWebTestCase;

class JsonHelperTest extends PrepareWebTestCase
{

    public function testEncodedJson()
    {
        $json = json_encode(['test'=>true,'type'=>'encoded']);

        $jsonHelper = new JsonHelper($json);

        $this->assertEquals(json_decode($json), $jsonHelper->getJson());

        $jsonHelper = new JsonHelper();
        $jsonHelper->setJson($json, true);

        $this->assertEquals(json_decode($json), $jsonHelper->getJson());
    }

    public function testDecodedJson()
    {
        $json = json_decode(json_encode(['test'=>true,'type'=>'decoded']));

        $jsonHelper = new JsonHelper();
        $jsonHelper->setJson($json, false);

        $this->assertEquals($json, $jsonHelper->getJson());
    }

}
