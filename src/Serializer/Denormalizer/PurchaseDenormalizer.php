<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\QRStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class PurchaseDenormalizer implements ContextAwareDenormalizerInterface
{


    public function __construct(private readonly ObjectNormalizer $normalizer, private EntityManagerInterface $entityManager)
    {}

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Purchase::class;
    }

    /**
     * @throws ORMException
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        /** @var Purchase $object */
        $object = $context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null;

        if ($object) {
            $product = $this->entityManager->getRepository(Product::class)->find(['id' => $data['product']]);
            $object->setProduct($product);
            $product->addPurchase($object);
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);

    }

}