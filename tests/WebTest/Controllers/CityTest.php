<?php
namespace App\Tests\WebTest\Controllers;

class CityTest extends \App\Tests\CoreTest
{
    /**
     * @var int
     */
    public $testId = 1;

    /**
     * @var array
     */
    public $mockData = [
        'state' => 1,
        'name' => 'Itaipava',
    ];

    /**
     * Test list states.
     */
    public function testGetCitys()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('state_index'),
            [], [], self::$loggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(true, isset($responseData['data']));
        $this->assertEquals(true, isset($responseData['paginator']));
        $this->assertEquals(true, isset($responseData['messages']));
    }

    /**
     * Test new state.
     */
    public function testPostCity()
    {
        $this->client->xmlHttpRequest('POST', $this->router->generate('state_new'),
            [], [], self::$loggedHeaders,
            json_encode($this->mockData)
        );

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(true, isset($responseData['data']['id']));
        $this->assertEquals(true, isset($responseData['messages']['success']));

        if (isset($responseData['data']['id'])) {
            $this->testId = $responseData['data']['id'];
        }
    }

    /**
     * Test get state by id.
     */
    public function testGetCity()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('state_show', ['id' => $this->testId]),
            [], [], self::$loggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(true, isset($responseData['data']['id']));
    }

    /**
     * Test update a state.
     */
    public function testPatchCity()
    {
        $this->client->xmlHttpRequest('PATCH', $this->router->generate('state_update', ['id' => $this->testId]),
            [], [], self::$loggedHeaders,
            json_encode([
                'name' => 'Patched Test City',
            ])
        );

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Patched Test City', $responseData['data']['name']);
        $this->assertEquals(true, isset($responseData['messages']['success']));
    }

    /**
     * Test delete a state.
     */
    public function testDeleteCity()
    {
        $this->client->xmlHttpRequest('DELETE', $this->router->generate('state_delete', ['id' => $this->testId]),
            [], [], self::$loggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(true, isset($responseData['messages']['success']));
    }
}
