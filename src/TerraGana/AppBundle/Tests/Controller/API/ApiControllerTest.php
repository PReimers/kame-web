<?php

namespace TerraGana\AppBundle\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    protected static $application;

    protected function setUp()
    {
        self::runCommand('doctrine:mongodb:schema:drop');
        self::runCommand('doctrine:mongodb:schema:create');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    public function testUserCreateAndSignIn()
    {
        $client = static::createClient();
        $id = 0;

        /* Create User */
        $client->request('POST', '/api/user/signIn', [], [], [], '{"email":"test@mail.com","googleId":"123"}');
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $client_response = $client->getResponse()->getContent();
        foreach (json_decode($client_response, true) as $key => $val) {
            $id = $key;
        }

        /* Sign In (Google-ID) */
        $client->request('POST', '/api/user/signIn', [], [], [], '{"email":"test@mail.com","googleId":"123"}');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Sign In (User-ID) */
        $client->request('POST', '/api/user/signIn', [], [], [], '{"email":"test@mail.com","id":"'.$id.'"}');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Sign In (failed) */
        $client->request('POST', '/api/user/signIn', [], [], [], '{"email":"test@mail.com","googleId":"321"}');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        /* Get all Users */
        $client->request('GET', '/api/user/all');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUser()
    {
        $client = static::createClient();
        $id = 0;

        /* Sign In */
        $client->request('POST', '/api/user/signIn', [], [], [], '{"email":"test@mail.com","googleId":"123"}');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [Response::HTTP_OK, Response::HTTP_CREATED]));
        foreach (json_decode($client->getResponse()->getContent(), true) as $key => $val) {
            $id = $key;
        }

        /* Delete User */
        $client->request('DELETE', '/api/user/delete/'.$id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        /* Delete User (failed) */
        $client->request('DELETE', '/api/user/delete/abc123');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertContains('{"message":"User not found"}', $client->getResponse()->getContent());
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $id = 0;

        /* Sign In */
        $client->request('POST', '/api/user/signIn', [], [], [], '{"email":"test@mail.com","googleId":"123"}');
        $this->assertTrue(in_array($client->getResponse()->getStatusCode(), [Response::HTTP_OK, Response::HTTP_CREATED]));
        $i = 1;
        foreach (json_decode($client->getResponse()->getContent(), true) as $key => $val) {
            $id = $key;
        }

        /* Edit User */
        $client->request('POST', '/api/user/edit/'.$id, [], [], [], '{"email":"test@mail.com","googleId":"123","username":"TestUser"}');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        /* Edit User (failed) */
        $client->request('POST', '/api/user/edit/abc123', [], [], [], '{"email":"test@mail.com","googleId":"123","username":"TestUser"}');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

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
