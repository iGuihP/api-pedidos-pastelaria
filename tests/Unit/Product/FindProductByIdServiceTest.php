<?php

use App\Models\ProductModel;
use Tests\TestCase;
use App\Services\Product\FindProductByIdService;
use App\Repositories\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FindProductByIdServiceTest extends TestCase
{
    protected $productRepository;
    protected $findProductByIdService;
    protected $clientRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->findProductByIdService = new FindProductByIdService($this->productRepository);

        $this->clientRequest = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

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

    public function testFindProductByIdEndpoint() {
        $product = ProductModel::factory()->create();
        $this->assertDatabaseHas('products', ['id' => $product->id]);
        $response = $this->clientRequest->get('/api/product/' . $product->id);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('name', $data, 'A chave "name" deve estar na resposta');
    }
}
