<?php

use Tests\TestCase;
use App\Services\Product\CreateProductService;
use App\Repositories\ProductRepositoryInterface;
use App\Traits\ClientRequestTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CreateProductServiceTest extends TestCase
{
    use ClientRequestTrait;
    protected $productRepository;
    protected $createProductService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->createProductService = new CreateProductService($this->productRepository);

        Log::shouldReceive('info');
    }

    public function testCreateProduct()
    {
        $productData = [
            'name' => 'New Product',
            'price' => 19.99,
        ];

        $imagePath = 'path/to/uploaded/image/product.jpg';
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->expects($this->once())
            ->method('store')
            ->with('/', 'local')
            ->willReturn($imagePath);

        $createdProduct = (object)['id' => 1];
        $this->productRepository->expects($this->once())
            ->method('create')
            ->with($productData['name'], $productData['price'], $imagePath)
            ->willReturn($createdProduct);

        $result = $this->createProductService->create($productData, $uploadedFile);

        $this->assertEquals($createdProduct->id, $result);
    }

    public function testCreateProductEndpoint()
    {
        $filename = md5(fake()->unique()->word()) . '.jpg';
        $image = imagecreatetruecolor(200, 200);
        $imagePath = storage_path('app/public/fake_images/' . $filename);
        imagejpeg($image, $imagePath);
        $imageContent = file_get_contents($imagePath);

        $response = $this->requestClient()->post('/api/product', [
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

        $this->assertEquals(201, $response->getStatusCode());
    }
}
