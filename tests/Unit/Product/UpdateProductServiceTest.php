<?php

use App\Models\ProductModel;
use Tests\TestCase;
use App\Services\Product\UpdateProductService;
use App\Repositories\ProductRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UpdateProductServiceTest extends TestCase
{
    protected $productRepository;
    protected $updateProductService;
    protected $requestClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->updateProductService = new UpdateProductService($this->productRepository);
        $this->requestClient = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

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
    
    public function testUpdateProductEndpoint()
    {
        $product = ProductModel::factory()->create();

        $filename = md5(fake()->unique()->word()) . '.jpg';
        $image = imagecreatetruecolor(200, 200);
        $imagePath = storage_path('app/public/fake_images/' . $filename);
        imagejpeg($image, $imagePath);
        $imageContent = file_get_contents($imagePath);

        $response = $this->requestClient->post('/api/product/' . $product->id, [
            'multipart' => [
                [
                    'name'     => 'name',
                    'contents' => fake()->word(),
                ],
                [
                    'name'     => 'price',
                    'contents' => fake()->randomFloat(2, 0, 100),
                ],
                [
                    'name'     => 'image',
                    'contents' => $imageContent,
                    'filename' => $filename,
                ],
            ],
        ]);

        File::delete($imagePath);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
