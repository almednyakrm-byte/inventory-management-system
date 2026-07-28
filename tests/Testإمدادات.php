<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\SuppliesController;
use App\Repository\SuppliesRepository;
use App\Entity\Supplies;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testإمدادات extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(SuppliesRepository::class);
        $this->controller = new SuppliesController($this->repository);
    }

    public function testGetSupplies()
    {
        $supplies = [
            new Supplies(1, 'Supply 1', 'Description 1'),
            new Supplies(2, 'Supply 2', 'Description 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($supplies);

        $response = $this->controller->getSupplies();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($supplies), $response->getContent());
    }

    public function testGetSupplyById()
    {
        $supply = new Supplies(1, 'Supply 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($supply);

        $response = $this->controller->getSupplyById(1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($supply), $response->getContent());
    }

    public function testGetSupplyByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getSupplyById(1);
    }

    public function testPostSupply()
    {
        $supply = new Supplies(1, 'Supply 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($supply)
            ->willReturn($supply);

        $request = new Request();
        $request->request->set('name', 'Supply 1');
        $request->request->set('description', 'Description 1');

        $response = $this->controller->postSupply($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($supply), $response->getContent());
    }

    public function testPutSupply()
    {
        $supply = new Supplies(1, 'Supply 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($supply);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($supply)
            ->willReturn($supply);

        $request = new Request();
        $request->request->set('name', 'Supply 1');
        $request->request->set('description', 'Description 1');

        $response = $this->controller->putSupply(1, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($supply), $response->getContent());
    }

    public function testPutSupplyNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Supply 1');
        $request->request->set('description', 'Description 1');

        $this->controller->putSupply(1, $request);
    }

    public function testDeleteSupply()
    {
        $supply = new Supplies(1, 'Supply 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($supply);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($supply);

        $response = $this->controller->deleteSupply(1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteSupplyNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deleteSupply(1);
    }
}