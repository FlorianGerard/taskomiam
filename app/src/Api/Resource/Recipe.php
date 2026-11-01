<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Recipe as RecipeEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use App\Api\Dto\RecipeCollection;

#[ApiResource(
    shortName: 'Recipe',
    stateOptions: new Options(entityClass: RecipeEntity::class),
    operations: [
        new Get(),
        new GetCollection(
            output: RecipeCollection::class
        ),
    ]
)]
#[Map(source: RecipeEntity::class)]
final class Recipe
{
    public int $id;

    public string $name;
}
