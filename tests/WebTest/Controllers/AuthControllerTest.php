<?php
namespace App\Tests\WebTest\Controllers;

use App\Tests\AbstractCoreTest;

class AuthControllerTest extends AbstractCoreTest
{
    /**
     * Test sucess session logged.
     */
    public function testAuthOptions()
    {
        $this->client->xmlHttpRequest(
            'OPTIONS',
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
    }

    /**
     * Test sucess session logged.
     */
    public function testAuthSuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/Auth/testAuthSuccess.json'), true);

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

        $respBody = json_decode($response->getContent(), true);
        ['data' => $data] = $respBody;

        $this->assertEquals($mock['response'], $respBody);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('enabled', $data);
    }

    /**
     * Test invalid session logged.
     */
    public function testAuthInvalid()
    {
        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('auth_check'),
            [],
            [],
            self::$failureLoggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * Test session logged unknow attibute.
     */
    public function testAuthUnknow()
    {
        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('auth_check'),
            [],
            [],
            self::$unkownAttrLoggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Test failure session not logged.
     */
    public function testAuthFailure()
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

    /**
     * Test access denied resource logged.
     */
    public function testAuthDenied()
    {
        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('state_index'),
            [],
            [],
            self::$deniedAttrLoggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(403, $response->getStatusCode());
    }
}
