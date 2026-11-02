<?php

namespace App\Api\Input;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use App\Entity\User;


final class UserUpdateInput
{
    #[Assert\Email]
    public ?string $email;

    #[Assert\Length(min: 10, minMessage: 'validators.length10')]
    #[Assert\Regex(
        pattern: '/[A-Z]/',
        message: 'validators.password.uppercase'
    )]
    #[Assert\Regex(
        pattern: '/[^a-zA-Z0-9]/',
        message: 'validators.password.special'
    )]
    #[Assert\Regex(
        pattern: '/\s/',
        match: false,
        message: 'validators.password.no_space'
    )]
    #[Map(target: 'plainPassword')]
    public ?string $password;

    #[Assert\Length(min: 2)]
    public ?string $firstname;

    #[Assert\Length(min: 2)]
    public ?string $lastname;
}
