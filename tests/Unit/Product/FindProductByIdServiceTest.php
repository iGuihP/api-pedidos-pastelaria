<?php

use PHPUnit\Framework\TestCase;
use App\Services\Product\FindProductByIdService;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FindProductByIdServiceTest extends TestCase
{
    protected $productRepository;
    protected $findProductByIdService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->findProductByIdService = new FindProductByIdService($this->productRepository);

        Log::shouldReceive('info');
    }

    public function testFindProductById()
    {
        $productId = 1;

        $product = (object)['id' => $productId, 'name' => 'Product Name', 'price' => 19.99];
        $this->productRepository->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $result = $this->findProductByIdService->find($productId);

        $this->assertEquals($product, $result);
    }

    public function testFindProductByIdNotFound()
    {
        $productId = 1;

        $this->productRepository->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product not found.");
        $this->expectExceptionCode(404);

        $this->findProductByIdService->find($productId);
    }
}
