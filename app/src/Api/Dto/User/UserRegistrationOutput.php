<?php

namespace App\Api\Dto\User;

use Symfony\Component\ObjectMapper\Attribute\Map;

final class UserRegistrationOutput
{
    public int $id;
    public string $email;

    #[Map(source: 'fullName')]
    public string $name;

    public string $token;
}
