<?php

declare(strict_types=1);

namespace App\Controllers;


use App\Services\ProductService;

class ProductController extends ControllerAbstract
{
    protected ProductService $productService;

    public function __construct($db, $requestMethod, $id)
    {
        parent::__construct($requestMethod, $id);

        $this->productService = new ProductService($db);
    }

    public function processRequest()
    {
        $response = null;

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->productService->getDetail($this->id);
                } else {
                    $response = $this->productService->getAll();
                };
                break;
            case 'POST':
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        if (! $response) {
            $response = $this->notFoundResponse();
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}
