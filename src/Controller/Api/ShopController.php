<?php

namespace App\Controller\Api;

use App\Dto\GroupRoleDto;
use App\Managers\PurchaseManager;
use App\DTO\PurchaseDto;
use App\Service\ResponseService;
use App\Service\PriceService;
use App\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/shop', name: 'api_shop_')]
class ShopController extends AbstractController
{

    use ExceptionHandlerTrait;

    #[Route('/', name: 'index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ShopController.php',
        ]);
    }

    #[Route('/calculate-price', name: 'calculate')]
    public function calculate(Request $request, ResponseService $responseService, SerializerInterface $serializer, PurchaseManager $purchaseManager, ValidatorInterface $validator): JsonResponse
    {
        $purchaseDto = $serializer->deserialize(
            $request->getContent(),
            PurchaseDto::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => new PurchaseDto()]
        );

        $errors = $validator->validate($purchaseDto);

        if (count($errors) > 0) {
            return $responseService->getResponse($this->handleValidatorErrorExceptions($errors), 'client');
        }

        return $responseService->getResponse(['price' => $purchaseManager->calculatePrice($purchaseDto)], 'client');
    }

    #[Route('/purchase', name: 'purchase')]
    public function purchase(Request $request, PurchaseManager $creator, ResponseService $responseService, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $purchaseDto = $serializer->deserialize(
            $request->getContent(),
            PurchaseDto::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => new PurchaseDto()]
        );

        $errors = $validator->validate($purchaseDto);
        if (count($errors) > 0) {
            return $this->handleValidatorErrorExceptions($errors);
        }

        try {
            $purchaseEntity = $creator->create($purchaseDto);
            $errors = $validator->validate($purchaseEntity);
            if (count($errors) > 0) {
                return $this->handleValidatorErrorExceptions($errors);
            }

            $entityManager->persist($purchaseEntity);
            $entityManager->flush();
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }

        return $responseService->getResponse($purchaseEntity, 'client');
    }
}
