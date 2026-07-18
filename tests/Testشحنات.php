<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Panther\Driver\BrowserKitDriver;
use Symfony\Component\Panther\PantherTestCase;
use App\Repository\شحناتRepository;
use App\Entity\شحنات;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

class Testشحنات extends WebTestCase
{
    private $client;
    private $repository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = Mockery::mock(شحناتRepository::class);
    }

    public function testGetAll(): void
    {
        $this->repository->shouldReceive('findAll')->andReturn([new شحنات()]);
        $this->client->request('GET', '/شحنات');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetOne(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturn(new شحنات());
        $this->client->request('GET', '/شحنات/' . $id);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetOneNotFound(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturnNull();
        $this->client->request('GET', '/شحنات/' . $id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test شحنات'];
        $this->repository->shouldReceive('create')->with($data)->andReturn(new شحنات());
        $this->client->request('POST', '/شحنات', ['json' => $data]);
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test شحنات'];
        $this->repository->shouldReceive('find')->with($id)->andReturn(new شحنات());
        $this->repository->shouldReceive('update')->with($id, $data)->andReturn(new شحنات());
        $this->client->request('PUT', '/شحنات/' . $id, ['json' => $data]);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Test شحنات'];
        $this->repository->shouldReceive('find')->with($id)->andReturnNull();
        $this->client->request('PUT', '/شحنات/' . $id, ['json' => $data]);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturn(new شحنات());
        $this->repository->shouldReceive('delete')->with($id);
        $this->client->request('DELETE', '/شحنات/' . $id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturnNull();
        $this->client->request('DELETE', '/شحنات/' . $id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetAll`: Test that the `GET /شحنات` endpoint returns a list of all شحنات.
- `testGetOne`: Test that the `GET /شحنات/{id}` endpoint returns a single شحنات by ID.
- `testGetOneNotFound`: Test that the `GET /شحنات/{id}` endpoint returns a 404 error when the ID is not found.
- `testCreate`: Test that the `POST /شحنات` endpoint creates a new شحنات.
- `testUpdate`: Test that the `PUT /شحنات/{id}` endpoint updates an existing شحنات.
- `testUpdateNotFound`: Test that the `PUT /شحنات/{id}` endpoint returns a 404 error when the ID is not found.
- `testDelete`: Test that the `DELETE /شحنات/{id}` endpoint deletes a شحنات.
- `testDeleteNotFound`: Test that the `DELETE /شحنات/{id}` endpoint returns a 404 error when the ID is not found.

Note that this test file uses Mockery to mock the `شحناتRepository` class, which allows us to isolate the dependencies of the `شحنات` controller and test its behavior in isolation.