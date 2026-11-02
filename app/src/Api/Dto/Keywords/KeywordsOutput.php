<?php

namespace App\Api\Dto\Keywords;

use App\Entity\Keywords;
use Symfony\Component\ObjectMapper\Attribute\Map;


#[Map(source: Keywords::class)]
final class KeywordsOutput
{
    public int $id;
    public string $name;
}
