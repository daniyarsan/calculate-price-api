<?php

namespace App\DTO;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseDto implements DtoInterface
{

    #[Assert\NotBlank(message: "Product should not be blank")]
    #[Assert\NotNull]
    private int $product;
    private ?string $taxNumber = null;
    private ?string $couponCode = null;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getTaxNumber()
    {
        return $this->taxNumber;
    }

    /**
     * @param mixed $taxNumber
     */
    public function setTaxNumber($taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return mixed
     */
    public function getCouponCode()
    {
        return $this->couponCode;
    }

    /**
     * @param mixed $couponCode
     */
    public function setCouponCode($couponCode): void
    {
        $this->couponCode = $couponCode;
    }
}