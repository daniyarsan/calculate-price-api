<?php

namespace App\Tests\Unit\Managers;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use App\Managers\PurchaseManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PurchaseManagerTest extends KernelTestCase
{

    /**
     * @dataProvider totalPriceDataProvider
     */
    public function testGetProductTotalPrice(int $expectedValue, $productData, $taxData = false, $couponData = false)
    {
        self::bootKernel();

        $product = new Product();
        $product->setName($productData['name']);
        $product->setPrice($productData['price']);

        $tax = null;
        if ($taxData) {
            $tax = new Tax();
            $tax->setFormat($taxData['format']);
            $tax->setCountry($taxData['country']);
            $tax->setPercentage($taxData['percentage']);
        }


        $coupon = null;
        if ($couponData) {
            $coupon = new Coupon();
            $coupon->setCouponCode($couponData['code']);
            $coupon->setType($couponData['type']);
            $coupon->setAmount($couponData['amount']);
        }

        $purchaseManager = self::getContainer()->get(PurchaseManager::class);

        $finalPrice = $purchaseManager->getProductTotalPrice($product, $tax, $coupon);

        $this->assertEquals($expectedValue, floor($finalPrice));
    }

    public static function totalPriceDataProvider(): array
    {
        return [
            'Check price with tax and coupon' => [
                102,
                ['name' => 'iphone', 'price' => 100],
                ['format' => 'DE123456789', 'country' => 'Germany', 'percentage' => 19],
                ['type' => 'percentage', 'code' => 'P15', 'amount' => 15],
            ],

            'Check price with tax' => [
                119,
                ['name' => 'iphone', 'price' => 100],
                ['format' => 'DE123456789', 'country' => 'Germany', 'percentage' => 19],
                null
            ],

            'Check price with coupon' => [
                85,
                ['name' => 'iphone', 'price' => 100],
                null,
                ['type' => 'percentage', 'code' => 'P15', 'amount' => 15],
            ],

            'Check price without tax and coupon' => [
                100,
                ['name' => 'iphone', 'price' => 100],
                null,
                null
            ],
        ];
    }
}
