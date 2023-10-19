<?php
use PHPUnit\Framework\TestCase;
use App\Services\Product\CreateProductService;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CreateProductServiceTest extends TestCase
{
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
}
