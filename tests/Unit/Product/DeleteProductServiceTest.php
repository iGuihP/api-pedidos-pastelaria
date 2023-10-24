<?php

use App\Models\ProductModel;
use Tests\TestCase;
use App\Traits\ClientRequestTrait;
use App\Services\Product\DeleteProductService;
use App\Repositories\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DeleteProductServiceTest extends TestCase
{
    use ClientRequestTrait;
    protected $productRepository;
    protected $deleteProductService;
    protected $requestClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->deleteProductService = new DeleteProductService($this->productRepository);
        Log::shouldReceive('info');
    }

    public function testDeleteProduct()
    {
        $productId = 1;

        $product = (object)['id' => $productId];
        $this->productRepository->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $this->productRepository->expects($this->once())
            ->method('delete')
            ->with($product);

        $this->deleteProductService->delete($productId);
    }

    public function testDeleteProductNotFound()
    {
        $productId = 1;

        $this->productRepository->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product not found.");
        $this->expectExceptionCode(404);

        $this->deleteProductService->delete($productId);
    }

    public function testDeletePustomerEndpoint()
    {
        $product = ProductModel::factory()->create();
        $response = $this->requestClient()->delete('/api/product/'.$product->id);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
