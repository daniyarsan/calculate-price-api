<?php

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseService
{

    public function __construct(private PaginatorInterface $paginator, private SerializerInterface $serializer)
    {}

    public function getResponse($data, string $groups = 'admin'): JsonResponse
    {
        return $this->json($data, Response::HTTP_OK, [], ['groups' => $groups, AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);
    }

    private function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }
}