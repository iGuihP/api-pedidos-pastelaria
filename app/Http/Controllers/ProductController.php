<?php

namespace App\Http\Controllers;

use App\Services\Product\CreateProductService;
use App\Services\Product\DeleteProductService;
use App\Services\Product\FindProductByFilterService;
use App\Services\Product\FindProductByIdService;
use App\Services\Product\ListAllProductsService;
use App\Services\Product\UpdateProductService;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function create(Request $request) {
        $params = $request->all();

        try {
            $this->validateRequestParameters(
                [
                    'name' => 'required|string',
                    'price' => 'required|numeric|gt:0',
                    'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
                ],
                $params
            );

            $productRepository = new ProductRepository();
            $createProductService = new CreateProductService($productRepository);
            $createdProductId = $createProductService->create($params, $request->file('image'));

            return response()->json([
                'newProductId' => $createdProductId
            ], 201);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to create a new product. Location: ProductController::create', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function findByFilters(Request $request) {
        $params = $request->all();

        try {
            $this->validateRequestParameters(
                [
                    'name' => 'required|string',
                ],
                $params
            );

            $productRepository = new ProductRepository();
            $findProductService = new FindProductByFilterService($productRepository);
            $productsFound = $findProductService->find($params);

            return response()->json([
                'data' => $productsFound
            ], 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find products by filters. Location: ProductController::findByFilters', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function findById($id) {
        try {
            $productRepository = new ProductRepository();
            $findProductByIdService = new FindProductByIdService($productRepository);
            $productsFound = $findProductByIdService->find((int) $id);

            return response()->json($productsFound, 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find products by ID. Location: ProductController::findById', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function listAll() {
        try {
            $productRepository = new ProductRepository();
            $listAllProductsService = new ListAllProductsService($productRepository);
            $productsFound = $listAllProductsService->list();

            return response()->json([
                'data' => $productsFound
            ], 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to list all products. Location: ProductController::listAll', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function update($id, Request $request) {
        $params = $request->all();
        try {
            $this->validateRequestParameters(
                [
                    'name' => 'required|string',
                    'price' => 'required|numeric|gt:0',
                    'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
                ],
                $params
            );

            $productRepository = new ProductRepository();
            $updateProductService = new UpdateProductService($productRepository);
            $updateProductService->update($id, $params, $request->file('image'));
            
            return response(null, 204);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to update a product. Location: ProductController::update', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function delete($id) {
        try {
            $productRepository = new ProductRepository();
            $deleteProductService = new DeleteProductService($productRepository);
            $deleteProductService->delete($id);
            
            return response(null, 204);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to delete a product. Location: ProductController::delete', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function viewImage($fileName) {
        try {
            return response()->file(storage_path('app/public/' . $fileName));
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to delete a product. Location: ProductController::viewImage', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }
}
