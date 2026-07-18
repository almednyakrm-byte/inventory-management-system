<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\SuppliersController;
use App\Repository\SuppliersRepository;
use App\Entity\Supplier;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestSuppliers extends TestCase
{
    private $suppliersController;
    private $suppliersRepository;

    protected function setUp(): void
    {
        $this->suppliersRepository = $this->createMock(SuppliersRepository::class);
        $this->suppliersController = new SuppliersController($this->suppliersRepository);
    }

    public function testGetSuppliers(): void
    {
        $expectedSuppliers = [
            new Supplier('Supplier 1', 'Address 1'),
            new Supplier('Supplier 2', 'Address 2'),
        ];

        $this->suppliersRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedSuppliers);

        $response = $this->suppliersController->getSuppliers();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedSuppliers), $response->getContent());
    }

    public function testGetSupplier(): void
    {
        $expectedSupplier = new Supplier('Supplier 1', 'Address 1');

        $this->suppliersRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedSupplier);

        $response = $this->suppliersController->getSupplier(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedSupplier), $response->getContent());
    }

    public function testGetSupplierNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->suppliersRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->suppliersController->getSupplier(1);
    }

    public function testPostSupplier(): void
    {
        $supplier = new Supplier('Supplier 1', 'Address 1');
        $expectedSupplier = new Supplier('Supplier 1', 'Address 1');

        $this->suppliersRepository
            ->expects($this->once())
            ->method('save')
            ->with($supplier)
            ->willReturn($expectedSupplier);

        $request = new Request([], [], ['supplier' => $supplier]);
        $response = $this->suppliersController->postSupplier($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedSupplier), $response->getContent());
    }

    public function testPutSupplier(): void
    {
        $supplier = new Supplier('Supplier 1', 'Address 1');
        $expectedSupplier = new Supplier('Supplier 1', 'Address 1');

        $this->suppliersRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($supplier);

        $this->suppliersRepository
            ->expects($this->once())
            ->method('save')
            ->with($supplier)
            ->willReturn($expectedSupplier);

        $request = new Request([], [], ['supplier' => $supplier]);
        $response = $this->suppliersController->putSupplier(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedSupplier), $response->getContent());
    }

    public function testPutSupplierNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $supplier = new Supplier('Supplier 1', 'Address 1');

        $this->suppliersRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], ['supplier' => $supplier]);
        $this->suppliersController->putSupplier(1, $request);
    }

    public function testDeleteSupplier(): void
    {
        $supplier = new Supplier('Supplier 1', 'Address 1');

        $this->suppliersRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($supplier);

        $this->suppliersRepository
            ->expects($this->once())
            ->method('remove')
            ->with($supplier);

        $response = $this->suppliersController->deleteSupplier(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteSupplierNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->suppliersRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->suppliersController->deleteSupplier(1);
    }
}