<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testإدارةالمنتجات extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetProducts()
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM products')
            ->willReturn($pdoMock);

        $this->client->request('GET', '/api/products', [], [], ['PDO' => $pdoMock]);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    }

    public function testCreateProduct()
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO products (name, price) VALUES (:name, :price)')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->with([':name' => 'Product 1', ':price' => 10.99]);

        $this->client->request('POST', '/api/products', [], ['name' => 'Product 1', 'price' => 10.99], [], ['PDO' => $pdoMock]);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    }

    public function testUpdateProduct()
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE products SET name = :name, price = :price WHERE id = :id')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->with([':name' => 'Product 1', ':price' => 10.99, ':id' => 1]);

        $this->client->request('PUT', '/api/products/1', [], ['name' => 'Product 1', 'price' => 10.99], [], ['PDO' => $pdoMock]);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    }

    public function testDeleteProduct()
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM products WHERE id = :id')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->client->request('DELETE', '/api/products/1', [], [], [], ['PDO' => $pdoMock]);

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses the `Client` class from Symfony to simulate HTTP requests to the API. It uses the `createMock` method from PHPUnit to create mock objects for the PDO statements. The `expects` method is used to specify the expected behavior of the mock objects.

The `testGetProducts` method tests the GET request to retrieve all products. The `testCreateProduct` method tests the POST request to create a new product. The `testUpdateProduct` method tests the PUT request to update an existing product. The `testDeleteProduct` method tests the DELETE request to delete a product.

Each test method uses the `client` object to send the request to the API and then asserts the response status code and content type.