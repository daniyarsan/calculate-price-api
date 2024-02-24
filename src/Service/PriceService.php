<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Entity\Product;
use App\Entity\Tax;

class PriceService
{

    public function calculatePrice(Product $product, ?Tax $tax, ?Coupon $coupon): int
    {
        $productPrice = $product->getPrice();
        if ($tax) {
            $productPrice += $tax->getAmountByPrice($productPrice) ;
        }
        if ($coupon) {
            $productPrice -= $coupon->getDiscountByPrice($productPrice);
        }

        return $productPrice;
    }



}