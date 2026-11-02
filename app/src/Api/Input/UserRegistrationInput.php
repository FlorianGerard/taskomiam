<?php

namespace App\Api\Input;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use App\Entity\User;

#[UniqueEntity(
    fields: ['email'],
    entityClass: User::class,
    message: 'Cette adresse email est déjà utilisée.'
)]
final class UserRegistrationInput
{

    #[Assert\NotBlank(message: 'validators.not_blank')]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank(message: 'validators.not_blank')]
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
    public string $password;

    #[Assert\NotBlank(message: 'validators.not_blank')]
    public string $firstname;

    #[Assert\NotBlank(message: 'validators.not_blank')]
    public string $lastname;
}
