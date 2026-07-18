<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\Mbi3atController;
use App\Repository\Mbi3atRepository;
use App\Entity\Mbi3at;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;

class TestMbi3at extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(Mbi3atRepository::class);
        $this->controller = new Mbi3atController($this->repository);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetMbi3at()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Mbi3at()]);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $response = $this->controller->getMbi3at($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostMbi3at()
    {
        $mbi3at = new Mbi3at();
        $mbi3at->setId(1);
        $mbi3at->setName('Test Mbi3at');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($mbi3at)
            ->willReturn($mbi3at);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->request->expects($this->once())
            ->method('request')
            ->with('name')
            ->willReturn('Test Mbi3at');

        $response = $this->controller->postMbi3at($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutMbi3at()
    {
        $mbi3at = new Mbi3at();
        $mbi3at->setId(1);
        $mbi3at->setName('Test Mbi3at');

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn($mbi3at);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($mbi3at)
            ->willReturn($mbi3at);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $this->request->expects($this->once())
            ->method('request')
            ->with('name')
            ->willReturn('Test Mbi3at');

        $response = $this->controller->putMbi3at($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMbi3at()
    {
        $mbi3at = new Mbi3at();
        $mbi3at->setId(1);

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn($mbi3at);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($mbi3at)
            ->willReturn($mbi3at);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $response = $this->controller->deleteMbi3at($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}