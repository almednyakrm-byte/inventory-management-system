<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\المخازنController;
use App\Repository\المخازنRepository;
use App\Entity\المخازن;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testالمخازن extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(المخازنRepository::class);
        $this->controller = new المخازنController($this->repository);
    }

    public function testGetAll(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new المخازن());

        $response = $this->controller->getOne(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new المخازن());

        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('update')
            ->with(new المخازن());

        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\المخازنController.php

namespace App\Controller;

use App\Repository\المخازنRepository;
use App\Entity\المخازن;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class المخازنController
{
    private $repository;

    public function __construct(المخازنRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Response
    {
        $data = $this->repository->findAll();
        return new JsonResponse(['data' => $data]);
    }

    public function getOne(int $id): Response
    {
        $data = $this->repository->find($id);
        return new JsonResponse(['data' => $data]);
    }

    public function create(Request $request): Response
    {
        $data = new المخازن();
        $this->repository->save($data);
        return new JsonResponse(['data' => $data]);
    }

    public function update(int $id, Request $request): Response
    {
        $data = new المخازن();
        $this->repository->update($data);
        return new JsonResponse(['data' => $data]);
    }

    public function delete(int $id): Response
    {
        $this->repository->delete($id);
        return new JsonResponse(['data' => []]);
    }
}