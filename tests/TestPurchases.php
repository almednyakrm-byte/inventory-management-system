<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\PurchasesController;
use App\Repository\PurchaseRepository;
use App\Service\PurchaseService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestPurchases extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(PurchaseRepository::class);
        $this->service = $this->createMock(PurchaseService::class);
        $this->controller = new PurchasesController($this->repository, $this->service);

        $this->pdo->method('prepare')->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->method('query')->willReturn($this->createMock(\PDOStatement::class));
    }

    public function testGetPurchases(): void
    {
        $this->repository->method('getAll')->willReturn([
            ['id' => 1, 'product' => 'Product 1', 'price' => 10.99],
            ['id' => 2, 'product' => 'Product 2', 'price' => 9.99],
        ]);

        $response = $this->controller->getPurchases();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([
            ['id' => 1, 'product' => 'Product 1', 'price' => 10.99],
            ['id' => 2, 'product' => 'Product 2', 'price' => 9.99],
        ]), $response->getBody()->getContents());
    }

    public function testCreatePurchase(): void
    {
        $data = [
            'product' => 'Product 1',
            'price' => 10.99,
        ];

        $this->service->method('create')->with($data)->willReturn(['id' => 1]);

        $response = $this->controller->createPurchase($data);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode(['id' => 1]), $response->getBody()->getContents());
    }

    public function testUpdatePurchase(): void
    {
        $data = [
            'id' => 1,
            'product' => 'Product 1',
            'price' => 10.99,
        ];

        $this->service->method('update')->with($data)->willReturn(['id' => 1]);

        $response = $this->controller->updatePurchase($data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode(['id' => 1]), $response->getBody()->getContents());
    }

    public function testDeletePurchase(): void
    {
        $id = 1;

        $this->service->method('delete')->with($id)->willReturn(true);

        $response = $this->controller->deletePurchase($id);

        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  **GET /purchases**: Tests the `getPurchases` method of the `PurchasesController` class, which retrieves all purchases from the database. It verifies that the response status code is 200 and the response body contains the expected JSON data.
2.  **POST /purchases**: Tests the `createPurchase` method of the `PurchasesController` class, which creates a new purchase in the database. It verifies that the response status code is 201 and the response body contains the expected JSON data.
3.  **PUT /purchases/{id}**: Tests the `updatePurchase` method of the `PurchasesController` class, which updates an existing purchase in the database. It verifies that the response status code is 200 and the response body contains the expected JSON data.
4.  **DELETE /purchases/{id}**: Tests the `deletePurchase` method of the `PurchasesController` class, which deletes a purchase from the database. It verifies that the response status code is 200.

Note that this test file uses PHPUnit's mocking capabilities to isolate the dependencies of the `PurchasesController` class and focus on the business logic of the CRUD operations. The `createMock` method is used to create mock objects for the `PDO` and `PurchaseRepository` classes, which are then configured to return specific values or throw exceptions as needed.