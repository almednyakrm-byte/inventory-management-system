<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\StockController;
use App\Repository\StockRepository;
use App\Entity\Stock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestStock extends TestCase
{
    private $stockController;
    private $stockRepository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->stockRepository = $this->createMock(StockRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->stockController = new StockController(
            $this->stockRepository,
            $this->entityManager,
            $this->router
        );
    }

    public function testGetAllStocks()
    {
        $expectedResponse = new JsonResponse(['stocks' => []]);
        $this->stockRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->stockController->getAllStocks($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetStockById()
    {
        $stockId = 1;
        $expectedResponse = new JsonResponse(['stock' => new Stock()]);
        $this->stockRepository->expects($this->once())
            ->method('find')
            ->with($stockId)
            ->willReturn(new Stock());

        $response = $this->stockController->getStockById($this->request, $stockId);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateStock()
    {
        $stock = new Stock();
        $expectedResponse = new JsonResponse(['stock' => $stock]);
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($stock);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->stockRepository->expects($this->once())
            ->method('create')
            ->with($stock)
            ->willReturn($stock);

        $response = $this->stockController->createStock($this->request, $stock);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateStock()
    {
        $stockId = 1;
        $stock = new Stock();
        $expectedResponse = new JsonResponse(['stock' => $stock]);
        $this->stockRepository->expects($this->once())
            ->method('find')
            ->with($stockId)
            ->willReturn($stock);
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($stock);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->stockRepository->expects($this->once())
            ->method('update')
            ->with($stock)
            ->willReturn($stock);

        $response = $this->stockController->updateStock($this->request, $stockId, $stock);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteStock()
    {
        $stockId = 1;
        $expectedResponse = new JsonResponse(['message' => 'Stock deleted successfully']);
        $this->stockRepository->expects($this->once())
            ->method('find')
            ->with($stockId)
            ->willReturn(new Stock());
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with(new Stock());
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->stockController->deleteStock($this->request, $stockId);
        $this->assertEquals($expectedResponse, $response);
    }
}