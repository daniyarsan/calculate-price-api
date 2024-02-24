<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

trait ExceptionHandlerTrait
{
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    )
    {
        $this->translator = $translator;
    }

    public function handleException(\Exception $exception): JsonResponse
    {
        $result = ['message' => $exception->getMessage()];
        return new JsonResponse($result, Response::HTTP_BAD_REQUEST);
    }

    public function handleValidatorErrorExceptions(ConstraintViolationListInterface $errors, array $errorMapping = []): JsonResponse
    {
        $result = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $propertyPath = $error->getPropertyPath();
            $errorLevel = count(explode('.', $propertyPath));

            if ($errorLevel === 2) {
                $exploded = explode('.', $propertyPath);
                $property = explode('[', $exploded[0])[0] ?? null;
                preg_match_all("/\[([^]]*)]/", $propertyPath, $matches);
                $index = $matches[1][0] ?? null;
                $result[$property][$index][$exploded[1]] = $error->getMessage();
            } else {
                $result[$errorMapping[$propertyPath] ?? $propertyPath] = $error->getMessage();
            }
        }

        return new JsonResponse(['message' => $result], Response::HTTP_BAD_REQUEST);
    }

}