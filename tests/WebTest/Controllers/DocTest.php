<?php
namespace App\Tests\WebTest\Controllers;

class DocTest extends \App\Tests\CoreTest
{
    /**
     * Test API doc json (openAPI).
     */
    public function testDocJson()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('app.swagger'));

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        ['data' => $data] = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('swagger', $data);
        $this->assertArrayHasKey('info', $data);
        $this->assertArrayHasKey('host', $data);
        $this->assertArrayHasKey('schemes', $data);
        $this->assertArrayHasKey('paths', $data);
        $this->assertArrayHasKey('definitions', $data);
        $this->assertArrayHasKey('securityDefinitions', $data);
        $this->assertArrayHasKey('security', $data);
    }

    /**
     * Test API doc ui (openAPI).
     */
    public function testDocUi()
    {
        $this->client->xmlHttpRequest('GET', $this->router->generate('app.swagger_ui'));

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
    }
}
