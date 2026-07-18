<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\FatouraController;
use App\Repository\FatouraRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestFatoura extends TestCase
{
    private $fatouraController;
    private $fatouraRepository;
    private $router;

    protected function setUp(): void
    {
        $this->fatouraRepository = $this->createMock(FatouraRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->fatouraController = new FatouraController($this->fatouraRepository, $this->router);
    }

    public function testGetFatouras()
    {
        $this->fatouraRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Fatoura 1'],
                ['id' => 2, 'name' => 'Fatoura 2'],
            ]);

        $request = new Request();
        $response = $this->fatouraController->getFatouras($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostFatoura()
    {
        $this->fatouraRepository->expects($this->once())
            ->method('save')
            ->with(['id' => 1, 'name' => 'Fatoura 1']);

        $request = new Request([], [], ['name' => 'Fatoura 1']);
        $response = $this->fatouraController->postFatoura($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutFatoura()
    {
        $this->fatouraRepository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Fatoura 1']);

        $request = new Request([], [], ['name' => 'Fatoura 1']);
        $response = $this->fatouraController->putFatoura(1, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteFatoura()
    {
        $this->fatouraRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->fatouraController->deleteFatoura(1, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'فواتير' module. It creates a mock object for the `FatouraRepository` and uses it to simulate the database operations. The tests cover the GET, POST, PUT, and DELETE requests.