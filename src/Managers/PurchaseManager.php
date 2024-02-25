<?php

namespace App\Managers;


use App\DTO\DtoInterface;
use App\DTO\PurchaseDto;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\Tax;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;
use App\Service\PriceService;
use App\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseManager implements CreatorInterface
{

    use ExceptionHandlerTrait;

    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
        private TaxRepository $taxRepository,
        private CouponRepository $couponRepository
    ){}

    private function getProduct(PurchaseDto $purchaseDto):Product
    {
        $product = $this->productRepository->find(['id' => $purchaseDto->getProduct()]);
        if (!$product) {
            throw new EntityNotFoundException('Product not found');
        }

        return $product;
    }


    public function calculatePrice(DtoInterface $dto): int
    {
        $product = $this->getProduct($dto);
        $coupon = $dto->getCouponCode() ? $this->couponRepository->findOneBy(['couponCode' => $dto->getCouponCode()]) : null;
        $tax = $dto->getTaxNumber() ? $this->taxRepository->findOneBy(['format' => $dto->getTaxNumber()]) : null;

        return  $this->getProductTotalPrice($product, $tax, $coupon);
    }

    public function create(DtoInterface $dto): Purchase
    {
        $product = $this->getProduct($dto);
        $tax = $dto->getTaxNumber() ? $this->taxRepository->findOneBy(['format' => $dto->getTaxNumber()]) : null;
        $coupon = $this->couponRepository->findOneBy(['couponCode' => $dto->getCouponCode()]);

        $entity = new Purchase();
        $entity->setProduct($product);
        $entity->setTaxNumber($tax ? $tax->getFormat() : null);
        $entity->setCoupon($coupon);
        $entity->setTotalPrice($this->getProductTotalPrice($product, $tax, $coupon));

        return $entity;
    }


    public function getProductTotalPrice(Product $product, ?Tax $tax, ?Coupon $coupon): int
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