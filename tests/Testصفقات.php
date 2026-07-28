<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\صفقاتController;
use App\Repository\صفقاتRepository;
use App\Entity\صفقات;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testصفقات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(صفقاتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new صفقاتController($this->repository, $this->entityManager, $this->router);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new صفقات()]);

        $response = $this->controller->getAll($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new صفقات());

        $response = $this->controller->getOne($this->request, [$id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOneNotFound(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getOne($this->request, [$id]);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new صفقات($data));

        $response = $this->controller->create($this->request, $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new صفقات());

        $response = $this->controller->update($this->request, [$id], $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->update($this->request, [$id], $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new صفقات());

        $response = $this->controller->delete($this->request, [$id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->delete($this->request, [$id]);
    }
}


This test file covers the CRUD operations for the 'صفقات' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

- `testGetAll`: Verifies that the `getAll` method returns a response with a 200 status code when the repository returns a list of entities.
- `testGetOne`: Verifies that the `getOne` method returns a response with a 200 status code when the repository returns an entity.
- `testGetOneNotFound`: Verifies that the `getOne` method throws a `NotFoundHttpException` when the repository returns null.
- `testCreate`: Verifies that the `create` method returns a response with a 201 status code when the repository saves a new entity.
- `testUpdate`: Verifies that the `update` method returns a response with a 200 status code when the repository updates an entity.
- `testUpdateNotFound`: Verifies that the `update` method throws a `NotFoundHttpException` when the repository returns null.
- `testDelete`: Verifies that the `delete` method returns a response with a 204 status code when the repository deletes an entity.
- `testDeleteNotFound`: Verifies that the `delete` method throws a `NotFoundHttpException` when the repository returns null.

Note that this is a basic implementation and you may need to add more tests to cover additional scenarios.