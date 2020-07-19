<?php
namespace App\Tests\WebTest\Controllers;

use App\Tests\AbstractCoreTest;

class IndexControllerTest extends AbstractCoreTest
{
    /**
     * Test API index.
     */
    public function testIndex()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('index'));

        $response = $this->client->getResponse();

        $this->assertSame(302, $response->getStatusCode());
    }

    /**
     * Test API healthy.
     */
    public function testHealthy()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('healthy'));

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        ['messages' => $messages] = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('info', $messages);
    }
}
