<?php

namespace App\Api\Dto\User;

use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: User::class)]
final class UserRecipeOutput
{
    public int $id;
    #[Map(source: 'fullName')]
    public string $name;
}
