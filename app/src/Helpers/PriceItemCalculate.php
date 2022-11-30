<?php

declare(strict_types=1);

namespace App\Helpers;

class PriceItemCalculate
{
    public static function calculateOne(array $product, array $order): float
    {
        $weight = $product['product_weight'];
        $width = $product['product_width'];
        $height = $product['product_height'];
        $depth = $product['product_depth'];
        $amazonPrice = $product['amazon_price'];

        $dimensionCoefficient = $order['dimension_coefficient'];
        $weightCoefficient = $order['weight_coefficient'];

        $feeByDimension = $width * $height * $depth * $dimensionCoefficient;
        $feeByWeight = $weight * $weightCoefficient;
        $shippingFee = max($feeByWeight, $feeByDimension);

        $itemPrice = $amazonPrice + $shippingFee;

        return $itemPrice;
    }

    public static function calculateAll(?array $products, array $order): float
    {
        if (empty($products)) {
            return 0;
        }

        $result = array_reduce(
            $products,
            function ($pre, $product) use ($order) {
                return $pre + self::calculateOne($product, $order);
            },
            0
        );

        return $result;
    }
}