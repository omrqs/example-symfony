<?php
namespace App\Tests\WebTest\Controllers;

use App\Tests\AbstractCoreTest;

class AuthControllerTest extends AbstractCoreTest
{
    /**
     * Test session logged.
     */
    public function testAuthSuccess()
    {
        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('auth_check'),
            [],
            [],
            self::$loggedHeaders
        );

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

    /**
     * Test session not logged.
     */
    public function testAuthFailed()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('auth_check'));

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        ['messages' => $messages] = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('notice', $messages);
    }
}
