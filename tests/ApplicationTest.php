<?php

namespace AppTests;

use Mockery;
use System\Axo;
use PHPUnit\Framework\TestCase;

class AxoApplicationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!defined('AXO_PATH')) {
            define('AXO_PATH', rtrim(dirname(__FILE__), '/').'/../');
        }
    }

    public function testApplicationLoads()
    {
        $app = Mockery::mock(Axo::class);
        $app->shouldReceive('version')
                ->andReturn(1.0);

        $this->assertTrue(is_float($app->version()));
    }

    public function testApplicationFailsOnMissingCommand()
    {
        $this->expectOutputString("\033[37;41mThe command [commandnotexist] does not exist\033[0m".PHP_EOL);

        $app = $this->getMockBuilder('System\Axo')
                    ->setMethods()
                    ->getMock();

        $app->run(['run', 'commandnotexist', 'test']);
    }

    public function testApplicationLoadCommandCustomDirectory()
    {
        $this->expectOutputString('SUCCESS');

        $app = $this->getMockBuilder('System\Axo')
                    ->setMethods()
                    ->getMock();

        $app->addCommandDirectory(__DIR__.'/Commands', 'AppTests\Commands');

        $app->run(['run', 'test', '--option1', '-o', 'argument']);
    }

    public function testApplicationComplainsMissingArgument()
    {
        $this->expectOutputString('php run test ARG1 [ARG2]'.PHP_EOL);

        $app = $this->getMockBuilder('System\Axo')
                    ->setMethods()
                    ->getMock();

        $app->addCommandDirectory(__DIR__.'/Commands', 'AppTests\Commands');

        $app->run(['run', 'test', '--option1', '-o']);
    }

    public function testApplicationComplainsMissingOption()
    {
        $this->expectOutputString('--option2 / -o option missing'.PHP_EOL);

        $app = $this->getMockBuilder('System\Axo')
                    ->setMethods()
                    ->getMock();

        $app->addCommandDirectory(__DIR__.'/Commands', 'AppTests\Commands');

        $app->run(['run', 'test', '--option1', 'argument']);
    }

    public function testApplicationInjectsDepedencyToConstructor()
    {
        $this->expectOutputString('Bag is initialised');

        $app = new \System\Axo;

        $app->addCommandDirectory(__DIR__.'/Commands', 'AppTests\Commands');

        $instance = $app->run(['run', 'inject']);
    }

    public function testApplicationInjectsDepedencyToRunCommand()
    {
        $this->expectOutputString('1 two');

        $app = new \System\Axo;

        $app->addCommandDirectory(__DIR__.'/Commands', 'AppTests\Commands');

        $instance = $app->run(['run', 'inject2', 1, 'two']);
    }
}
