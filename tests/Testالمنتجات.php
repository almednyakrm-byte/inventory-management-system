<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProductsController;
use App\Repository\ProductsRepository;
use App\Entity\Product;
use Mockery;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testالمنتجات extends TestCase
{
    private $controller;
    private $repository;
    private $product;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(ProductsRepository::class);
        $this->controller = new ProductsController($this->repository);
        $this->product = new Product();
    }

    public function testGetAllProducts()
    {
        $this->repository->shouldReceive('findAll')->andReturn([$this->product]);
        $response = $this->controller->getAllProducts();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProductById()
    {
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->product);
        $response = $this->controller->getProductById(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateProduct()
    {
        $this->repository->shouldReceive('save')->with($this->product)->andReturn($this->product);
        $request = new Request();
        $request->request->set('name', 'Product Name');
        $request->request->set('price', 10.99);
        $response = $this->controller->createProduct($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProduct()
    {
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->product);
        $this->repository->shouldReceive('save')->with($this->product)->andReturn($this->product);
        $request = new Request();
        $request->request->set('name', 'Updated Product Name');
        $request->request->set('price', 11.99);
        $response = $this->controller->updateProduct(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteProduct()
    {
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->product);
        $this->repository->shouldReceive('remove')->with($this->product);
        $response = $this->controller->deleteProduct(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// ProductsController.php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsController
{
    private $repository;

    public function __construct(ProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllProducts()
    {
        $products = $this->repository->findAll();
        return new JsonResponse($products);
    }

    public function getProductById($id)
    {
        $product = $this->repository->find($id);
        return new JsonResponse($product);
    }

    public function createProduct(Request $request)
    {
        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $this->repository->save($product);
        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    public function updateProduct($id, Request $request)
    {
        $product = $this->repository->find($id);
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $this->repository->save($product);
        return new JsonResponse($product);
    }

    public function deleteProduct($id)
    {
        $product = $this->repository->find($id);
        $this->repository->remove($product);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}