<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Api\Dto\Recipe\RecipeCollectionOutput;
use App\Api\Mapper\EntityTransformer;
use App\Entity\Recipe as RecipeEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ApiResource(
    shortName: 'Recipe',
    stateOptions: new Options(entityClass: RecipeEntity::class),
    operations: [
        new Get(),
        new GetCollection(
            parameters: [
                'category' => new QueryParameter(
                    filter: new ExactFilter(),
                    property: 'category.id'
                )
            ],
            output: RecipeCollectionOutput::class
        ),
    ]
)]

#[Map(source: RecipeEntity::class)]
final class Recipe
{
    public int $id;
    public string $name;
    public string $description;
    #[Map(
        source: 'author',
        transform: EntityTransformer::class
    )]
    public object $author;
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
