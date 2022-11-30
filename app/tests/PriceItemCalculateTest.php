<?php

declare(strict_types=1);

namespace Tests;

use App\Helpers\PriceItemCalculate;
use PHPUnit\Framework\TestCase;

class PriceItemCalculateTest extends TestCase
{
    public function testCalculateOneMaxDimension()
    {
        $product = array(
            'product_weight' => 5,
            'product_height' => 2,
            'product_width' => 3,
            'product_depth' => 4,
            'amazon_price' => 10,
        );
        $order = array(
            'dimension_coefficient' => 5,
            'weight_coefficient' => 3,
        );

        $result = PriceItemCalculate::calculateOne($product, $order);

        $this->assertEquals(130, $result);
    }

    public function testCalculateOneMaxWeight()
    {
        $product = array(
            'product_weight' => 5,
            'product_height' => 2,
            'product_width' => 3,
            'product_depth' => 4,
            'amazon_price' => 10,
        );
        $order = array(
            'dimension_coefficient' => 1,
            'weight_coefficient' => 10,
        );

        $result = PriceItemCalculate::calculateOne($product, $order);

        $this->assertEquals(60, $result);
    }

}