<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Coupon;
use PHPUnit\Framework\TestCase;

class CouponTest extends TestCase
{

    /**
     * @dataProvider couponDataProvider
     */
    public function testCalculateAmountByPrice(int $expectedDiscount, array $couponData, int $totalPrice)
    {
        $coupon = new Coupon();
        $coupon->setType($couponData['type']);
        $coupon->setCouponCode($couponData['code']);
        $coupon->setAmount($couponData['amount']);

        $this->assertEquals($expectedDiscount, $coupon->getDiscountByPrice($totalPrice));
    }


    /* Check different way of discount calculation. Percentage vs amount type */
    static function couponDataProvider(): array
    {
        return [
            'Check discount by percentage type' => [
                150,
                ['type' => 'percentage', 'code' => 'P15', 'amount' => 15],
                1000
            ],

            'Check discount by amount type' => [
                100,
                ['type' => 'amount', 'code' => 'D100', 'amount' => 100],
                1000
            ],
        ];
    }
}
