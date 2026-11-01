<?php

namespace App\Api\Dto;

use App\Entity\Category;
use Symfony\Component\ObjectMapper\Attribute\Map;


#[Map(source: Category::class)]
final class CategoryOutput
{
    public int $id;
    public string $name;
    public ?string $description;
}
