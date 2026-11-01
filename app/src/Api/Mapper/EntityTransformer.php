<?php

namespace App\Api\Mapper;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

final class EntityTransformer implements TransformCallableInterface
{
    public function __construct(
        private readonly ObjectMapperInterface $mapper,
        private readonly array $dtoMap,
    ) {}

    public function __invoke(
        mixed $value,
        object $source,
        ?object $target
    ): mixed {
        if ($value === null) {
            return null;
        }

        if (is_iterable($value)) {
            $results = [];

            foreach ($value as $entity) {
                $results[] = $this->transformEntity($entity);
            }

            return $results;
        }

        return $this->transformEntity($value);
    }

    private function transformEntity(object $entity): object
    {
        $entityClass = $entity::class;

        $dtoClass = $this->dtoMap[$entityClass]
            ?? throw new \InvalidArgumentException(
                sprintf('Aucun DTO configuré pour %s', $entityClass)
            );

        return $this->mapper->map($entity, $dtoClass);
    }
}
