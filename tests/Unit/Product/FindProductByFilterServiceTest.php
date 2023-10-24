<?php

use App\Models\ProductModel;
use Tests\TestCase;
use App\Services\Product\FindProductByFilterService;
use App\Repositories\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FindProductByFilterServiceTest extends TestCase
{
    protected $productRepository;
    protected $findProductByFilterService;
    protected $requestClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->findProductByFilterService = new FindProductByFilterService($this->productRepository);
        
        $this->requestClient = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

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

    public function testFindProductsByFiltersEndpoint() {
        $product = ProductModel::factory()->create();
        $response = $this->requestClient->get('/api/product/filters', [
            'query' => [
                'name' => $product->name,
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertGreaterThan(0, count($data['data']), 'A resposta deve conter mais de um elemento no array');
    }

    public function testFindProductsNotFoundByFiltersEndpoint() {
        $response = $this->requestClient->get('/api/product/filters', [
            'query' => [
                'name' => 'product_not_found',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertCount(0, $data['data'], 'A resposta do data deve estar vazia');
    }
}
