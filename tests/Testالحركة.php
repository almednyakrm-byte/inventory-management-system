<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حركةController;
use App\Repository\حركةRepository;
use App\Entity\حركة;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testحركة extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->controller = new حركةController($this->repository = $this->createMock(حركةRepository::class));
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository->method('getEntityManager')->willReturn($this->entityManager);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->method('findAll')->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetById()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(new حركة());
        $response = $this->controller->getById($id);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(null);
        $this->controller->getById($id);
    }

    public function testCreate()
    {
        $request = new Request();
        $request->request->set('name', 'حركة جديدة');
        $expectedResponse = ['data' => ['name' => 'حركة جديدة']];
        $this->entityManager->method('persist')->with(new حركة())->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testUpdate()
    {
        $request = new Request();
        $request->request->set('name', 'حركة محدثة');
        $id = 1;
        $expectedResponse = ['data' => ['name' => 'حركة محدثة']];
        $this->repository->method('find')->with($id)->willReturn(new حركة());
        $this->entityManager->method('persist')->with(new حركة())->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $response = $this->controller->update($id, $request);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $request = new Request();
        $request->request->set('name', 'حركة محدثة');
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(null);
        $this->controller->update($id, $request);
    }

    public function testDelete()
    {
        $id = 1;
        $this->entityManager->method('remove')->with(new حركة())->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $response = $this->controller->delete($id);
        $this->assertEquals(['message' => 'حركة deleted successfully'], $response->toArray());
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(null);
        $this->controller->delete($id);
    }
}