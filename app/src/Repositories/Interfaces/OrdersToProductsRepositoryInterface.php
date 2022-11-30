<?php

namespace App\Repositories\Interfaces;

interface OrdersToProductsRepositoryInterface extends RepositoryInterfaceAbstract
{
    function findAllProductsByOrderIds(array $orderIds): array;
}