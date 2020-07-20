<?php
namespace App\Tests\WebTest\Controllers;

use App\Tests\AbstractCoreTest;

class CityControllerTest extends AbstractCoreTest
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
     * Test OPTIONS list cities.
     */
    public function testGetCitiesOptions()
    {
        $this->client->xmlHttpRequest(
            'OPTIONS',
            $this->router->generate('city_index'),
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
     * Test success list cities.
     */
    public function testGetCitiesSuccess()
    {
        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('city_index'),
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
        
        $this->assertArrayHasKey('cities', $data);

        foreach (self::$paginatorKeys as $key) {
            $this->assertArrayHasKey($key, $paginator);
        }
    }

    /**
     * Test success filtered list cities.
     */
    public function testGetCitiesFilteredSuccess()
    {
        $route = $this->router->generate('city_index');
        $qs = [
            'name' => 'rj',
            'sort' => 'state',
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
        
        $this->assertArrayHasKey('cities', $data);

        foreach (self::$paginatorKeys as $key) {
            $this->assertArrayHasKey($key, $paginator);
        }
    }

    /**
     * Test OPTIONS new city.
     */
    public function testPostCityOptions()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testPostCitySuccess.json'), true);

        $this->client->xmlHttpRequest(
            'OPTIONS',
            $this->router->generate('city_new'),
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
    }

    /**
     * Test success new city.
     * @depends testPostCityOptions
     */
    public function testPostCitySuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testPostCitySuccess.json'), true);

        $this->client->xmlHttpRequest(
            'POST',
            $this->router->generate('city_new'),
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

        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('id', $data['city']);
        $this->assertArrayHasKey('success', $messages);
    }

    /**
     * Test failure new city with error.
     */
    public function testPostCityFailure()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testPostCityFailure.json'), true);

        $this->client->xmlHttpRequest(
            'POST',
            $this->router->generate('city_new'),
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

        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('id', $data['city']);
        $this->assertArrayHasKey('name', $data['city']);
        $this->assertArrayHasKey('state', $data['city']);
        $this->assertArrayHasKey('error', $messages);
    }

    /**
     * Test OPTIONS get city by id.
     */
    public function testGetCityOptions()
    {
        $this->client->xmlHttpRequest(
            'OPTIONS',
            $this->router->generate('city_show', ['id' => $this->testId]),
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
     * Test success get city by id.
     *
     * @depends testGetCityOptions
     * @depends testPostCitySuccess
     */
    public function testGetCitySuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testGetCitySuccess.json'), true);

        $this->client->xmlHttpRequest(
            'GET',
            $this->router->generate('city_show', ['id' => $this->testId]),
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

        $this->assertArrayHasKey('id', $data['city']);
    }

    /**
     * Test OPTIONS update a city.
     *
     * @depends testGetCityOptions
     */
    public function testPatchCityOptions()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testPatchCitySuccess.json'), true);

        $this->client->xmlHttpRequest(
            'OPTIONS',
            $this->router->generate('city_update', ['id' => $this->testId]),
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
    }

    /**
     * Test success update a city.
     *
     * @depends testPatchCityOptions
     * @depends testGetCitySuccess
     */
    public function testPatchCitySuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testPatchCitySuccess.json'), true);

        $this->client->xmlHttpRequest(
            'PATCH',
            $this->router->generate('city_update', ['id' => $this->testId]),
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

        $this->assertArrayHasKey('name', $data['city']);
        $this->assertArrayHasKey('success', $messages);
    }

    /**
     * Test OPTIONS delete a city.
     *
     * @depends testPatchCitySuccess
     */
    public function testDeleteCityOptions()
    {
        $this->client->xmlHttpRequest(
            'DELETE',
            $this->router->generate('city_delete', ['id' => $this->testId]),
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
     * Test success delete a city.
     *
     * @depends testDeleteCityOptions
     * @depends testPatchCitySuccess
     */
    public function testDeleteCitySuccess()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/City/testDeleteCitySuccess.json'), true);

        $this->client->xmlHttpRequest(
            'DELETE',
            $this->router->generate('city_delete', ['id' => $this->testId]),
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
     * Test failure delete a city.
     *
     * @depends testDeleteCitySuccess
     */
    public function testDeleteCityFailure()
    {
        $this->client->xmlHttpRequest(
            'DELETE',
            $this->router->generate('city_delete', ['id' => $this->testId404]),
            [],
            [],
            self::$loggedHeaders
        );

        $response = $this->client->getResponse();

        $this->assertSame(404, $response->getStatusCode());
    }
}
