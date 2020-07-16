<?php
namespace App\Helpers;

use App\Entity\State;
use App\Helper\CoreHelper;
use PHPUnit\Framework\TestCase;

class CoreHelperTest extends TestCase
{
    public function testDenormalize()
    {
        // Set
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testDenormalize.json'), true);
        
        // Action
        $data = CoreHelper::denormalize($mock['request']);

        // Assertions
        $this->assertEquals($data, $mock['response']);
    }

    public function testObjectsToArray()
    {
        // Set
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testObjectsToArray.json'), true);
        $mock['request'] = [
            new State(),
        ];
        
        // Action
        $data = CoreHelper::objectsToArray($mock['request']);

        // Assertions
        $this->assertEquals($data, $mock['response']);
    }
}
