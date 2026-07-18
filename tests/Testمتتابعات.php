<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\متتابعاتRepository;
use App\Entity\متتابعات;
use App\Controller\متتابعاتController;

class Testمتتابعات extends WebTestCase
{
    private $client;
    private $router;
    private $tokenStorage;
    private $metatabaatRepository;
    private $metatabaatController;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::getContainer()->get(RouterInterface::class);
        $this->tokenStorage = static::getContainer()->get(TokenStorageInterface::class);
        $this->metatabaatRepository = $this->createMock(متتابعاتRepository::class);
        $this->metatabaatController = new متتابعاتController($this->metatabaatRepository);
    }

    public function testGetMetatabaat(): void
    {
        $this->metatabaatRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new متتابعات()]);

        $request = Request::create('/metatabaat', 'GET');
        $response = $this->metatabaatController->getMetatabaat($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostMetatabaat(): void
    {
        $metatabaat = new متتابعات();
        $metatabaat->setId(1);
        $metatabaat->setName('Test Metatabaat');

        $this->metatabaatRepository->expects($this->once())
            ->method('save')
            ->with($metatabaat)
            ->willReturn($metatabaat);

        $request = Request::create('/metatabaat', 'POST', [], [], [], json_encode(['name' => 'Test Metatabaat']));
        $response = $this->metatabaatController->postMetatabaat($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutMetatabaat(): void
    {
        $metatabaat = new متتابعات();
        $metatabaat->setId(1);
        $metatabaat->setName('Test Metatabaat');

        $this->metatabaatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($metatabaat);

        $this->metatabaatRepository->expects($this->once())
            ->method('save')
            ->with($metatabaat)
            ->willReturn($metatabaat);

        $request = Request::create('/metatabaat/1', 'PUT', [], [], [], json_encode(['name' => 'Updated Test Metatabaat']));
        $response = $this->metatabaatController->putMetatabaat($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMetatabaat(): void
    {
        $metatabaat = new متتابعات();
        $metatabaat->setId(1);
        $metatabaat->setName('Test Metatabaat');

        $this->metatabaatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($metatabaat);

        $this->metatabaatRepository->expects($this->once())
            ->method('remove')
            ->with($metatabaat);

        $request = Request::create('/metatabaat/1', 'DELETE');
        $response = $this->metatabaatController->deleteMetatabaat($request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This is a basic example and you may need to adjust it according to your actual code and requirements. Also, make sure to replace `App\Controller\متتابعاتController` and `App\Repository\متتابعاتRepository` with the actual namespace of your controller and repository classes.