<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Slim\Http\Response;

class BaseTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_base(): void
    {
        // $mock = $this->getMockBuilder('\Controllers\HomeController')
        //     ->disableOriginalConstructor()
        //     ->getMock();

        $this->assertTrue(true);
    }
}
