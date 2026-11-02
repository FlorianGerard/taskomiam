<?php

namespace App\Api\Dto\Recipe;

use App\Entity\RecipeIngredient;
use App\Enum\Unit;
use App\Entity\Ingredient;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: RecipeIngredient::class)]
final class RecipeIngredientOutput
{
    public int $id;
    public string $quantity;
    #[Map(
        source: 'unit',
        transform: [self::class, 'mapUnit']
    )]
    public string $unit;
    #[Map(
        source: 'ingredient',
        transform: [self::class, 'mapIngredientName']
    )]
    public string $name;


    public static function mapUnit(?Unit $unit): string
    {
        return $unit?->value ?? '';
    }
    public static function mapIngredientName(?Ingredient $ingredient): string
    {
        return $ingredient?->getName() ?? '';
    }
}
