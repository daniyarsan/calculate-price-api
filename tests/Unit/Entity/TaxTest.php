<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Tax;
use PHPUnit\Framework\TestCase;

class TaxTest extends TestCase
{
    public function testCalculateAmountByPrice()
    {
        $tax = new Tax();
        $tax->setCountry('Russia');
        $tax->setFormat('RU123456789');
        $tax->setPercentage(25);
        $totalPrice = 1000;

        $this->assertEquals(250, $tax->getAmountByPrice($totalPrice));
    }
}
