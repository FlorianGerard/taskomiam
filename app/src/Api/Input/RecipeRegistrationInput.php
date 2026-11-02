<?php

namespace App\Api\Input;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\ObjectMapper\Attribute\Map;

final class RecipeRegistrationInput
{

    #[Assert\NotBlank(
        message: 'Le nom de la recette doit être renseigné.',
        normalizer: 'trim',
    )]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le nom de la recette doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de la recette ne peut pas dépasser {{ limit }} caractères.',
        normalizer: 'trim',
    )]
    public string $name;

    public ?string $description;

    #[Assert\Positive(message: 'Le nombre de portions doit être un nombre positif.')]
    public ?int $servings;

    #[Assert\PositiveOrZero(message: 'Le temps de préparation doit être 0 ou un nombre positif.')]
    public ?int $preparation_time;

    #[Assert\PositiveOrZero(message: 'Le temps de cuisson doit être 0 ou un nombre positif.')]
    public ?int $cooking_time;
}
