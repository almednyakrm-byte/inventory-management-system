<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StockController;
use App\Repository\StockRepository;
use App\Service\StockService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testمخزونات extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(StockRepository::class);
        $this->service = $this->createMock(StockService::class);
        $this->controller = new StockController($this->repository, $this->service);
    }

    public function testGetAllStocks()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Stock 1'],
                ['id' => 2, 'name' => 'Stock 2'],
            ]);

        $response = $this->controller->getAllStocks();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetStockById()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Stock 1']);

        $response = $this->controller->getStockById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetStockByIdNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getStockById(1);
    }

    public function testCreateStock()
    {
        $this->service->expects($this->once())
            ->method('create')
            ->with(['name' => 'Stock 1'])
            ->willReturn(['id' => 1, 'name' => 'Stock 1']);

        $request = new Request([], [], ['name' => 'Stock 1']);
        $response = $this->controller->createStock($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateStock()
    {
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Stock 1'])
            ->willReturn(['id' => 1, 'name' => 'Stock 1']);

        $request = new Request([], [], ['name' => 'Stock 1']);
        $response = $this->controller->updateStock(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateStockNotFound()
    {
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Stock 1'])
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $request = new Request([], [], ['name' => 'Stock 1']);
        $this->controller->updateStock(1, $request);
    }

    public function testDeleteStock()
    {
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $response = $this->controller->deleteStock(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteStockNotFound()
    {
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(false);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->deleteStock(1);
    }
}