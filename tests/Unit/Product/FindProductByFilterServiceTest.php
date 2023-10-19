<?php

use PHPUnit\Framework\TestCase;
use App\Services\Product\FindProductByFilterService;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FindProductByFilterServiceTest extends TestCase
{
    protected $productRepository;
    protected $findProductByFilterService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->findProductByFilterService = new FindProductByFilterService($this->productRepository);

        Log::shouldReceive('info');
    }

    public function testFindProductByFilters()
    {
        $productName = 'Product Name';

        $products = [
            (object)['id' => 1, 'name' => $productName, 'price' => 19.99],
            (object)['id' => 2, 'name' => $productName, 'price' => 29.99],
        ];

        $this->productRepository->expects($this->once())
            ->method('findByFilters')
            ->with($productName)
            ->willReturn($products);

        $result = $this->findProductByFilterService->find(['name' => $productName]);

        $this->assertEquals($products, $result);
    }

    public function testFindProductByFiltersNotFound()
    {
        $productName = 'Non-Existent Product';

        $this->productRepository->expects($this->once())
            ->method('findByFilters')
            ->with($productName)
            ->willReturn([]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product not found.");
        $this->expectExceptionCode(404);

        $this->findProductByFilterService->find(['name' => $productName]);
    }
}
