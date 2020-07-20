<?php
namespace App\Tests\WebTest\Controllers;

use App\Tests\AbstractCoreTest;

class StateControllerTest extends AbstractCoreTest
{
    /**
     * @var int
     */
    public $testId = 1;

    /**
     * @var int
     */
    public $testId404 = 100;

    /**
     * Test success list states.
     */
    public function testGetStatesSuccess()
    {
        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('state_index'),
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
            
        ['data' => $data, 'paginator' => $paginator] = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('states', $data);

        foreach (self::$paginatorKeys as $key) {
            $this->assertArrayHasKey($key, $paginator);
        }
    }

    /**
     * Test success filtered list states.
     */
    public function testGetStatesFilteredSuccess()
    {
        $route = $this->router->generate('state_index');
        $qs = [
            'abrev' => 'rj',
            'sort' => 'name',
            'order' => 'desc',
            'page' => 1,
            'limit' => 10,
        ];

        $url = sprintf('%s?%s', $route, http_build_query($qs));

        $this->client->xmlHttpRequest(
            'GET',
            $url,
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
            
        ['data' => $data, 'paginator' => $paginator] = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('states', $data);

        foreach (self::$paginatorKeys as $key) {
            $this->assertArrayHasKey($key, $paginator);
        }
    }

    /**
     * Test success new state.
     */
    public function testPostStateSuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/State/testPostStateSuccess.json'), true);

        $this->client->xmlHttpRequest(
            'POST',
            $this->router->generate('state_new'),
            [],
            [],
            self::$loggedHeaders,
            json_encode($mock['request'])
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
        ['data' => $data, 'messages' => $messages] = $respBody;

        $this->assertEquals($mock['response'], $respBody);

        $this->assertArrayHasKey('state', $data);
        $this->assertArrayHasKey('id', $data['state']);
        $this->assertArrayHasKey('success', $messages);
    }

    /**
     * Test failure new state with error.
     */
    public function testPostStateFailure()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/State/testPostStateFailure.json'), true);

        $this->client->xmlHttpRequest(
            'POST',
            $this->router->generate('state_new'),
            [],
            [],
            self::$loggedHeaders,
            json_encode($mock['request'])
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
        ['data' => $data, 'messages' => $messages] = $respBody;

        $this->assertEquals($mock['response'], $respBody);

        $this->assertArrayHasKey('state', $data);
        $this->assertArrayHasKey('id', $data['state']);
        $this->assertArrayHasKey('name', $data['state']);
        $this->assertArrayHasKey('error', $messages);
    }

    /**
     * Test success get state by id.
     *
     * @depends testPostStateSuccess
     */
    public function testGetStateSuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/State/testGetStateSuccess.json'), true);

        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('state_show', ['id' => $this->testId]),
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

        $this->assertArrayHasKey('id', $data['state']);
    }

    /**
     * Test success update a state.
     *
     * @depends testGetStateSuccess
     */
    public function testPatchStateSuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/State/testPatchStateSuccess.json'), true);

        $this->client->xmlHttpRequest(
            'PATCH',
            $this->router->generate('state_update', ['id' => $this->testId]),
            [],
            [],
            self::$loggedHeaders,
            json_encode($mock['request'])
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
        ['data' => $data, 'messages' => $messages] = $respBody;

        $this->assertEquals($mock['response'], $respBody);

        $this->assertArrayHasKey('name', $data['state']);
        $this->assertArrayHasKey('success', $messages);
    }

    /**
     * Test success delete a state.
     *
     * @depends testPatchStateSuccess
     */
    public function testDeleteStateSuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/State/testDeleteStateSuccess.json'), true);

        $this->client->xmlHttpRequest(
            'DELETE',
            $this->router->generate('state_delete', ['id' => $this->testId]),
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
        ['messages' => $messages] = $respBody;

        $this->assertEquals($mock['response'], $respBody);


        $this->assertArrayHasKey('success', $messages);
    }

    /**
     * Test failure delete a state.
     *
     * @depends testDeleteStateSuccess
     */
    public function testDeleteStateFailure()
    {
        $this->client->xmlHttpRequest(
            'DELETE',
            $this->router->generate('state_delete', ['id' => $this->testId404]),
            [],
            [],
            self::$loggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(404, $response->getStatusCode());
    }
}
