<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ProduitController;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestProduitController extends TestCase
{
    private $controller;
    private $produitRepository;

    protected function setUp(): void
    {
        $this->produitRepository = $this->createMock(ProduitRepository::class);
        $this->controller = new ProduitController($this->produitRepository);
    }

    public function testGetProduits(): void
    {
        $produits = [
            ['id' => 1, 'nom' => 'Produit 1'],
            ['id' => 2, 'nom' => 'Produit 2'],
        ];

        $this->produitRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($produits);

        $response = $this->controller->getProduits();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($produits), $response->getContent());
    }

    public function testGetProduit(): void
    {
        $produit = ['id' => 1, 'nom' => 'Produit 1'];

        $this->produitRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($produit);

        $response = $this->controller->getProduit(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($produit), $response->getContent());
    }

    public function testGetProduitNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->produitRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getProduit(1);
    }

    public function testCreateProduit(): void
    {
        $produit = ['id' => 1, 'nom' => 'Produit 1'];
        $data = ['nom' => 'Produit 1'];

        $this->produitRepository->expects($this->once())
            ->method('save')
            ->with($produit);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->createProduit($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateProduit(): void
    {
        $produit = ['id' => 1, 'nom' => 'Produit 1'];
        $data = ['nom' => 'Produit 2'];

        $this->produitRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($produit);

        $this->produitRepository->expects($this->once())
            ->method('save')
            ->with($produit);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->updateProduit(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateProduitNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $data = ['nom' => 'Produit 2'];

        $this->produitRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], [], [], [], json_encode($data));
        $this->controller->updateProduit(1, $request);
    }

    public function testDeleteProduit(): void
    {
        $produit = ['id' => 1, 'nom' => 'Produit 1'];

        $this->produitRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($produit);

        $this->produitRepository->expects($this->once())
            ->method('remove')
            ->with($produit);

        $response = $this->controller->deleteProduit(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProduitNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->produitRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deleteProduit(1);
    }
}


This test file covers the following scenarios:

- `testGetProduits`: Tests the `getProduits` method to ensure it returns a list of all products.
- `testGetProduit`: Tests the `getProduit` method to ensure it returns a single product by ID.
- `testGetProduitNotFound`: Tests the `getProduit` method to ensure it throws a `NotFoundHttpException` when the product is not found.
- `testCreateProduit`: Tests the `createProduit` method to ensure it creates a new product.
- `testUpdateProduit`: Tests the `updateProduit` method to ensure it updates an existing product.
- `testUpdateProduitNotFound`: Tests the `updateProduit` method to ensure it throws a `NotFoundHttpException` when the product is not found.
- `testDeleteProduit`: Tests the `deleteProduit` method to ensure it deletes a product.
- `testDeleteProduitNotFound`: Tests the `deleteProduit` method to ensure it throws a `NotFoundHttpException` when the product is not found.