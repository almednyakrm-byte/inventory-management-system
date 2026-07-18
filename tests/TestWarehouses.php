<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\WarehousesController;
use App\Repository\WarehousesRepository;
use App\Service\WarehousesService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestWarehouses extends TestCase
{
    private $warehousesController;
    private $warehousesRepository;
    private $warehousesService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->warehousesRepository = $this->createMock(WarehousesRepository::class);
        $this->warehousesService = $this->createMock(WarehousesService::class);
        $this->warehousesController = new WarehousesController($this->warehousesRepository, $this->warehousesService);
    }

    public function testGetWarehouses(): void
    {
        $expectedResponse = ['warehouses' => []];
        $this->warehousesRepository->expects($this->once())
            ->method('getAllWarehouses')
            ->willReturn($expectedResponse);
        $response = $this->warehousesController->getWarehouses();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateWarehouse(): void
    {
        $warehouseData = ['name' => 'Test Warehouse', 'address' => 'Test Address'];
        $expectedResponse = ['warehouse' => $warehouseData];
        $this->warehousesService->expects($this->once())
            ->method('createWarehouse')
            ->with($warehouseData)
            ->willReturn($expectedResponse);
        $response = $this->warehousesController->createWarehouse($warehouseData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateWarehouse(): void
    {
        $warehouseId = 1;
        $warehouseData = ['name' => 'Updated Warehouse', 'address' => 'Updated Address'];
        $expectedResponse = ['warehouse' => $warehouseData];
        $this->warehousesService->expects($this->once())
            ->method('updateWarehouse')
            ->with($warehouseId, $warehouseData)
            ->willReturn($expectedResponse);
        $response = $this->warehousesController->updateWarehouse($warehouseId, $warehouseData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteWarehouse(): void
    {
        $warehouseId = 1;
        $this->warehousesRepository->expects($this->once())
            ->method('deleteWarehouse')
            ->with($warehouseId);
        $response = $this->warehousesController->deleteWarehouse($warehouseId);
        $this->assertTrue($response);
    }
}



// WarehousesController.php

namespace App\Controller;

use App\Repository\WarehousesRepository;
use App\Service\WarehousesService;

class WarehousesController
{
    private $warehousesRepository;
    private $warehousesService;

    public function __construct(WarehousesRepository $warehousesRepository, WarehousesService $warehousesService)
    {
        $this->warehousesRepository = $warehousesRepository;
        $this->warehousesService = $warehousesService;
    }

    public function getWarehouses(): array
    {
        return $this->warehousesRepository->getAllWarehouses();
    }

    public function createWarehouse(array $data): array
    {
        return $this->warehousesService->createWarehouse($data);
    }

    public function updateWarehouse(int $id, array $data): array
    {
        return $this->warehousesService->updateWarehouse($id, $data);
    }

    public function deleteWarehouse(int $id): bool
    {
        return $this->warehousesRepository->deleteWarehouse($id);
    }
}