<?php

use App\Models\ProductModel;
use Tests\TestCase;
use App\Services\Product\ListAllProductsService;
use App\Repositories\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Log;

class ListAllProductsServiceTest extends TestCase
{
    protected $productRepository;
    protected $listAllProductsService;
    protected $clientRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->listAllProductsService = new ListAllProductsService($this->productRepository);

        $this->clientRequest = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

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

    public function testListAllProductsEndpoint() {
        ProductModel::factory()->create();
        $response = $this->clientRequest->get('/api/product/');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertGreaterThan(0, count($data['data']), 'A resposta deve conter mais de um elemento no array');
    }
}
