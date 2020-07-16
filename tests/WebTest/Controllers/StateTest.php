<?php
namespace App\Tests\WebTest\Controllers;

class StateTest extends \App\Tests\CoreTest
{
    /**
     * @var int
     */
    public $testId = 1;

    /**
     * Test list states.
     */
    public function testGetStates()
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
            
        ['data' => $data, 'paginator' => $paginator, 'messages' => $messages] = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('states', $data);

        $this->assertArrayHasKey('current', $paginator);
        $this->assertArrayHasKey('last', $paginator);
        $this->assertArrayHasKey('current', $paginator);
        $this->assertArrayHasKey('numItemsPerPage', $paginator);
        $this->assertArrayHasKey('first', $paginator);
        $this->assertArrayHasKey('pageCount', $paginator);
        $this->assertArrayHasKey('totalCount', $paginator);
        $this->assertArrayHasKey('pageRange', $paginator);
        $this->assertArrayHasKey('startPage', $paginator);
        $this->assertArrayHasKey('endPage', $paginator);
        $this->assertArrayHasKey('pagesInRange', $paginator);
        $this->assertArrayHasKey('firstPageInRange', $paginator);
        $this->assertArrayHasKey('lastPageInRange', $paginator);
        $this->assertArrayHasKey('currentItemCount', $paginator);
        $this->assertArrayHasKey('firstItemNumber', $paginator);
        $this->assertArrayHasKey('lastItemNumber', $paginator);
    }

    /**
     * Test new state.
     */
    public function testPostState()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testPostState.json'), true);

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
     * Test get state by id.
     * 
     * @depends testPostState
     */
    public function testGetState()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testGetState.json'), true);

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
     * Test update a state.
     * 
     * @depends testGetState
     */
    public function testPatchState()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testPatchState.json'), true);

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
     * Test delete a state.
     * 
     * @depends testPatchState
     */
    public function testDeleteState()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testDeleteState.json'), true);

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
}
