<?php
use PHPUnit\Framework\TestCase;
use App\Services\Product\UpdateProductService;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class UpdateProductServiceTest extends TestCase
{
    protected $productRepository;
    protected $updateProductService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->updateProductService = new UpdateProductService($this->productRepository);

        Log::shouldReceive('info');
    }

    public function testUpdateProduct()
    {
        $productId = 1;
        $productData = [
            'name' => 'Updated Product Name',
            'price' => 19.99,
            'description' => 'Updated product description',
        ];
        $image = null; 

        $product = (object)['id' => $productId];
        $this->productRepository->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $this->productRepository->expects($this->once())
            ->method('update')
            ->with($product, $productData);

        $this->updateProductService->update($productId, $productData, $image);
    }

    public function testUpdateProductNotFound()
    {
        $productId = 1;
        $productData = [
            'name' => 'Updated Product Name',
            'price' => 19.99,
        ];
        $image = null; 

        $this->productRepository->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product not found.");
        $this->expectExceptionCode(404);

        $this->updateProductService->update($productId, $productData, $image);
    }
}
