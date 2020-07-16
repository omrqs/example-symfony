<?php
namespace App\Tests\WebTest\Controllers;

class CityTest extends \App\Tests\CoreTest
{
    /**
     * @var int
     */
    public $testId = 1;

    /**
     * Test list cities.
     */
    public function testGetCities()
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
            
        ['data' => $data, 'paginator' => $paginator, 'messages' => $messages] = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('cities', $data);

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
     * Test new city.
     */
    public function testPostCity()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testPostCity.json'), true);

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
     * Test get city by id.
     * 
     * @depends testPostCity
     */
    public function testGetCity()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testGetCity.json'), true);

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
     * Test update a city.
     * 
     * @depends testGetCity
     */
    public function testPatchCity()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testPatchCity.json'), true);

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
     * Test delete a city.
     * 
     * @depends testPatchCity
     */
    public function testDeleteCity()
    {
        $mock = json_decode(\file_get_contents(__DIR__.'/Fixtures/testDeleteCity.json'), true);

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
}
