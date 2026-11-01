<?php

namespace App\Api\Dto;

use App\Entity\Recipe as RecipeEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use App\Api\Mapper\EntityTransformer;

#[Map(source: RecipeEntity::class)]
final class RecipeCollection
{
    public int $id;
    public string $name;
    public string $description;
    public string $author;
    public int $preparationTime;
    public int $cookingTime;
    public \DateTimeInterface $createdAt;
    #[Map(
        source: 'recipeIngredients',
        transform: EntityTransformer::class
    )]
    public array $ingredients = [];
    #[Map(
        source: 'category',
        transform: EntityTransformer::class
    )]
    public array $categories = [];

    #[Map(
        source: 'keywords',
        transform: EntityTransformer::class
    )]
    public array $keywords = [];
}
