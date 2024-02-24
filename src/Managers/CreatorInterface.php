<?php

namespace App\Managers;

use App\DTO\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

interface CreatorInterface
{
    public function create(DtoInterface $dto);
}