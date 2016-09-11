<?php

namespace TerraGana\AppBundle\Tests\Helper;

use TerraGana\AppBundle\Document\User;
use TerraGana\AppBundle\Helper\UserHelper;
use TerraGana\AppBundle\Tests\PrepareWebTestCase;

class UserHelperTest extends PrepareWebTestCase
{
    public function testConvertJson()
    {
        $json = json_encode(['test' => true, 'username' => 'Test']);
        $returnedJson = json_encode(['id' => null, 'googleId' => null, 'username' => 'Test', 'email' => null]);

        $userHelper = new UserHelper($json);

        $this->assertEquals(json_decode($returnedJson), $userHelper->convertJson($json));
    }

    public function testUpdateUser()
    {
        $user = new User();

        $json = json_encode(['googleId' => '123', 'username' => 'Test']);

        $userHelper = new UserHelper();
        $updatedUser = $userHelper->updateUser($user, $userHelper->convertJson($json));

        $this->assertEquals($user->getGoogleId(), $updatedUser->getGoogleId());
        $this->assertEquals($user->getUsername(), $updatedUser->getUsername());
        $this->assertNotNull($updatedUser->getUpdatedAt());
    }
}
