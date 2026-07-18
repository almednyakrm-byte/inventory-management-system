<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use App\Entity\Materials;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Testمواد extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MaterialsRepository::class);
        $this->controller = new MaterialsController($this->repository);
        $this->request = new Request();
    }

    public function testGetMaterials(): void
    {
        $materials = [new Materials()];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($materials);

        $response = $this->controller->getMaterials($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($materials, json_decode($response->getContent(), true));
    }

    public function testGetMaterial(): void
    {
        $material = new Materials();
        $material->setId(1);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $response = $this->controller->getMaterial($this->request, 1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($material, json_decode($response->getContent(), true));
    }

    public function testGetMaterialNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getMaterial($this->request, 1);
    }

    public function testPostMaterial(): void
    {
        $material = new Materials();
        $material->setName('Test Material');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($material)
            ->willReturn($material);

        $this->request->request->set('name', 'Test Material');
        $response = $this->controller->postMaterial($this->request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($material, json_decode($response->getContent(), true));
    }

    public function testPostMaterialBadRequest(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->request->request->set('name', '');
        $this->controller->postMaterial($this->request);
    }

    public function testPutMaterial(): void
    {
        $material = new Materials();
        $material->setId(1);
        $material->setName('Test Material');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($material)
            ->willReturn($material);

        $this->request->request->set('name', 'Test Material');
        $response = $this->controller->putMaterial($this->request, 1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($material, json_decode($response->getContent(), true));
    }

    public function testPutMaterialNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->putMaterial($this->request, 1);
    }

    public function testDeleteMaterial(): void
    {
        $material = new Materials();
        $material->setId(1);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($material);

        $response = $this->controller->deleteMaterial($this->request, 1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteMaterialNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deleteMaterial($this->request, 1);
    }
}