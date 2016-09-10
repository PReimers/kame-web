<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 10.09.2016
 * Time: 20:40.
 */
namespace TerraGana\AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class PrepareWebTestCase extends WebTestCase
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
}
