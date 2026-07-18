// File: TestProducts.php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ProductsController;
use App\Repository\ProductsRepository;
use App\Service\ProductsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestProducts extends TestCase
{
    private $productsController;
    private $productsRepository;
    private $productsService;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->productsRepository = $this->createMock(ProductsRepository::class);
        $this->productsService = $this->createMock(ProductsService::class);
        $this->productsController = new ProductsController($this->productsRepository, $this->productsService);
    }

    public function testGetProducts(): void
    {
        $this->productsRepository->expects($this->once())
            ->method('getAllProducts')
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]);

        $response = $this->productsController->getProducts();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['products' => [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
        ]], $response->getContent());
    }

    public function testGetProductById(): void
    {
        $this->productsRepository->expects($this->once())
            ->method('getProductById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Product 1']);

        $response = $this->productsController->getProductById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['product' => ['id' => 1, 'name' => 'Product 1']], $response->getContent());
    }

    public function testGetProductByIdNotFound(): void
    {
        $this->productsRepository->expects($this->once())
            ->method('getProductById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->productsController->getProductById(1);
    }

    public function testCreateProduct(): void
    {
        $request = new Request([], [], ['product' => ['name' => 'Product 3']]);

        $this->productsService->expects($this->once())
            ->method('createProduct')
            ->with(['name' => 'Product 3'])
            ->willReturn(['id' => 3, 'name' => 'Product 3']);

        $response = $this->productsController->createProduct($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['product' => ['id' => 3, 'name' => 'Product 3']], $response->getContent());
    }

    public function testUpdateProduct(): void
    {
        $request = new Request([], [], ['product' => ['id' => 1, 'name' => 'Product 1 Updated']]);

        $this->productsService->expects($this->once())
            ->method('updateProduct')
            ->with(['id' => 1, 'name' => 'Product 1 Updated'])
            ->willReturn(['id' => 1, 'name' => 'Product 1 Updated']);

        $response = $this->productsController->updateProduct(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['product' => ['id' => 1, 'name' => 'Product 1 Updated']], $response->getContent());
    }

    public function testDeleteProduct(): void
    {
        $this->productsService->expects($this->once())
            ->method('deleteProduct')
            ->with(1)
            ->willReturn(true);

        $response = $this->productsController->deleteProduct(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}