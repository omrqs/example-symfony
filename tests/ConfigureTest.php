<?php

namespace App\Tests;

class ConfigureTest extends \App\Tests\CoreTest
{
    /**
     * Configure environment.
     */
    public function testConfigure()
    {
        $this->assertEquals(true, $this->buildDb());
    }
}
