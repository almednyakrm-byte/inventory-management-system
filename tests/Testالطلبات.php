<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\OrdersController;
use App\Repository\OrdersRepository;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testالطلبات extends TestCase
{
    private $controller;
    private $ordersRepository;
    private $entityManager;
    private $router;

    protected function setUp(): void
    {
        $this->ordersRepository = $this->createMock(OrdersRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->controller = new OrdersController($this->ordersRepository, $this->entityManager, $this->router);
    }

    public function testGetOrders()
    {
        $this->ordersRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Order()]);

        $response = $this->controller->getOrders();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateOrder()
    {
        $order = new Order();
        $order->setName('Test Order');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = new Request([], [], ['name' => 'Test Order']);
        $response = $this->controller->createOrder($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateOrder()
    {
        $order = new Order();
        $order->setId(1);
        $order->setName('Test Order');

        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($order);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = new Request([], [], ['name' => 'Updated Test Order']);
        $response = $this->controller->updateOrder(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteOrder()
    {
        $order = new Order();
        $order->setId(1);

        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($order);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($order);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deleteOrder(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'الطلبات' module. It uses mocked PDO statements to simulate the database interactions. The tests verify that the correct HTTP status codes and response headers are returned for each operation.