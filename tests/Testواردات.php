<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\ImportsRepository;
use App\Entity\Imports;
use App\Controller\ImportsController;

class Testواردات extends WebTestCase
{
    private $client;
    private $router;
    private $importsRepository;
    private $importsController;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::getContainer()->get(RouterInterface::class);
        $this->importsRepository = $this->createMock(ImportsRepository::class);
        $this->importsController = new ImportsController($this->importsRepository);
    }

    public function testGetAllImports()
    {
        $this->importsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Imports()]);

        $request = Request::create('/واردات', 'GET');
        $response = $this->importsController->index($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetImportById()
    {
        $import = new Imports();
        $this->importsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($import);

        $request = Request::create('/واردات/1', 'GET');
        $response = $this->importsController->show($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetImportByIdNotFound()
    {
        $this->importsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = Request::create('/واردات/1', 'GET');
        $this->expectException(NotFoundHttpException::class);

        $this->importsController->show($request);
    }

    public function testCreateImport()
    {
        $import = new Imports();
        $this->importsRepository->expects($this->once())
            ->method('save')
            ->with($import)
            ->willReturn($import);

        $request = Request::create('/واردات', 'POST', [], [], [], json_encode(['name' => 'Test']));
        $response = $this->importsController->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateImport()
    {
        $import = new Imports();
        $this->importsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($import);
        $this->importsRepository->expects($this->once())
            ->method('save')
            ->with($import)
            ->willReturn($import);

        $request = Request::create('/واردات/1', 'PUT', [], [], [], json_encode(['name' => 'Test']));
        $response = $this->importsController->update($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateImportNotFound()
    {
        $this->importsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = Request::create('/واردات/1', 'PUT', [], [], [], json_encode(['name' => 'Test']));
        $this->expectException(NotFoundHttpException::class);

        $this->importsController->update($request);
    }

    public function testDeleteImport()
    {
        $import = new Imports();
        $this->importsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($import);
        $this->importsRepository->expects($this->once())
            ->method('remove')
            ->with($import);

        $request = Request::create('/واردات/1', 'DELETE');
        $response = $this->importsController->delete($request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteImportNotFound()
    {
        $this->importsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = Request::create('/واردات/1', 'DELETE');
        $this->expectException(NotFoundHttpException::class);

        $this->importsController->delete($request);
    }
}


This test file covers the following scenarios:

1.  **GET /واردات**: Tests retrieving all imports.
2.  **GET /واردات/{id}**: Tests retrieving a specific import by ID.
3.  **GET /واردات/{id} (NotFound)**: Tests retrieving a non-existent import by ID.
4.  **POST /واردات**: Tests creating a new import.
5.  **PUT /واردات/{id}**: Tests updating an existing import.
6.  **PUT /واردات/{id} (NotFound)**: Tests updating a non-existent import.
7.  **DELETE /واردات/{id}**: Tests deleting an existing import.
8.  **DELETE /واردات/{id} (NotFound)**: Tests deleting a non-existent import.

Each test method uses the `createMock` method to create a mock object for the `ImportsRepository` class. The mock object is then configured to return specific values or throw exceptions based on the test scenario.

The `importsController` object is created with the mock repository, and the corresponding action is called with a simulated request. The test then asserts the expected response status code and content type.

Note that this is a basic example, and you may need to modify the test code to fit your specific use case and requirements. Additionally, you should consider using a more robust testing framework, such as Symfony's built-in testing tools, to write more comprehensive and efficient tests.