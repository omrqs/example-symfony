<?php
namespace App\Helpers;

use App\Entity\State;
use App\Helper\CoreHelper;
use PHPUnit\Framework\TestCase;

class CoreHelperTest extends TestCase
{
    public function testDenormalizeSuccess()
    {
        // Set
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/Core/testDenormalizeSuccess.json'), true);
        
        // Action
        $data = CoreHelper::denormalize($mock['request']);

        // Assertions
        $this->assertEquals($data, $mock['response']);
    }

    public function testDenormalizeLongSuccess()
    {
        // Set
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/Core/testDenormalizeLongSuccess.json'), true);
        
        // Action
        $data = CoreHelper::denormalize($mock['request']);

        // Assertions
        $this->assertEquals($data, $mock['response']);
    }

    public function testObjectsToArraySuccess()
    {
        // Set
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/Core/testObjectsToArraySuccess.json'), true);
        $mock['request'] = [
            new State(),
        ];
        
        // Action
        $data = CoreHelper::objectsToArray($mock['request']);

        // Assertions
        $this->assertEquals($data, $mock['response']);
    }
}
