<?php

namespace App\Tests\Controller;

use App\Controller\StockController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testالمخزون extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new StockController($this->pdoMock);
    }

    public function testGetAllStocks()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM المخزون')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAllStocks();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateStock()
    {
        $data = [
            'name' => 'stock1',
            'quantity' => 10,
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المخزون (name, quantity) VALUES (:name, :quantity)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->createStock(Request::create('/المخزون', 'POST', [], [], [], json_encode($data)));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateStock()
    {
        $data = [
            'name' => 'stock1',
            'quantity' => 20,
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المخزون SET name = :name, quantity = :quantity WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->updateStock(Request::create('/المخزون/1', 'PUT', [], [], [], json_encode($data)));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteStock()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المخزون WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteStock(Request::create('/المخزون/1', 'DELETE'));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetAllStocks`: Tests the `getAllStocks` method of the `StockController` class, which should return a list of all stocks.
- `testCreateStock`: Tests the `createStock` method of the `StockController` class, which should create a new stock and return a 201 response.
- `testUpdateStock`: Tests the `updateStock` method of the `StockController` class, which should update an existing stock and return a 200 response.
- `testDeleteStock`: Tests the `deleteStock` method of the `StockController` class, which should delete a stock and return a 200 response.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to replace the `StockController` class with your actual controller class.