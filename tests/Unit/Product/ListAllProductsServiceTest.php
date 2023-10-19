<?php

use PHPUnit\Framework\TestCase;
use App\Services\Product\ListAllProductsService;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ListAllProductsServiceTest extends TestCase
{
    protected $productRepository;
    protected $listAllProductsService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->listAllProductsService = new ListAllProductsService($this->productRepository);

        Log::shouldReceive('info');
    }

    public function testListAllProducts()
    {
        $products = [
            (object)['id' => 1, 'name' => 'Product 1', 'price' => 19.99],
            (object)['id' => 2, 'name' => 'Product 2', 'price' => 29.99],
        ];

        $this->productRepository->expects($this->once())
            ->method('listAll')
            ->willReturn($products);

        $result = $this->listAllProductsService->list();

        $this->assertEquals($products, $result);
    }

    public function testListAllProductsNotFound()
    {
        $this->productRepository->expects($this->once())
            ->method('listAll')
            ->willReturn([]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product not found.");
        $this->expectExceptionCode(404);

        $this->listAllProductsService->list();
    }
}
