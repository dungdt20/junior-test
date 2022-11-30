<?php
namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepositoryImplement;

class ProductService extends ServiceAbstract
{

    private ProductRepositoryInterface $productRepository;

    public function __construct($db)
    {
        $this->productRepository = new ProductRepositoryImplement($db);
    }

    public function getAll(): array
    {
        $result = $this->productRepository->findAll();
        return $this->returnSuccessResponse($result);
    }

    public function getDetail($id): array
    {
        $result = $this->productRepository->find($id);
        if (! $result) {
            return $result;
        }
        return $this->returnSuccessResponse($result);
    }
}
