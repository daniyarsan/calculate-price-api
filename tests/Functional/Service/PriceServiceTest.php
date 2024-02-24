<?php

namespace App\Tests\UnitTest\Service;

use App\Service\PriceService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriceServiceTest extends KernelTestCase
{

    public function testPriceCalculation()
    {
        self::bootKernel();
        $priceService = self::getContainer()->get(PriceService::class);

        dd($priceService);
    }
}
