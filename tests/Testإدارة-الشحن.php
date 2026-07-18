<?php

declare(strict_types=1);

namespace App\Tests;

use App\Controller\ShipmentController;
use App\Repository\ShipmentRepository;
use App\Service\ShipmentService;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

class Testإدارةالشحن extends TestCase
{
    private ShipmentController $shipmentController;
    private ShipmentRepository $shipmentRepository;
    private ShipmentService $shipmentService;

    protected function setUp(): void
    {
        $this->shipmentRepository = $this->prophesize(ShipmentRepository::class)->reveal();
        $this->shipmentService = new ShipmentService($this->shipmentRepository);
        $this->shipmentController = new ShipmentController($this->shipmentService);
    }

    public function testGetAllShipments(): void
    {
        $request = $this->createRequest('GET', '/shipments');
        $response = $this->shipmentController->getAllShipments($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testGetShipmentById(): void
    {
        $request = $this->createRequest('GET', '/shipments/1');
        $response = $this->shipmentController->getShipmentById($request, ['id' => 1]);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testCreateShipment(): void
    {
        $data = [
            'name' => 'Test Shipment',
            'description' => 'Test shipment description',
        ];

        $request = $this->createRequest('POST', '/shipments', $data);
        $response = $this->shipmentController->createShipment($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateShipment(): void
    {
        $data = [
            'name' => 'Updated Test Shipment',
            'description' => 'Updated test shipment description',
        ];

        $request = $this->createRequest('PUT', '/shipments/1', $data);
        $response = $this->shipmentController->updateShipment($request, ['id' => 1]);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteShipment(): void
    {
        $request = $this->createRequest('DELETE', '/shipments/1');
        $response = $this->shipmentController->deleteShipment($request, ['id' => 1]);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(204, $response->getStatusCode());
    }

    private function createRequest(string $method, string $uri, array $data = []): ServerRequestInterface
    {
        $streamFactory = new StreamFactory();
        $request = (new \Slim\Psr7\Factory\ServerRequestFactory())->createRequest($method, $uri);
        $request = $request->withParsedBody($data);

        return $request;
    }
}