<?php

namespace App\Tests;

use App\Tests\AbstractCoreTest;

class ConfigureTest extends AbstractCoreTest
{
    /**
     * Configure environment.
     */
    public function testConfigure()
    {
        $this->assertEquals(true, $this->buildDb());
    }
}
