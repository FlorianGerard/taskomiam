<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function findSimilarNames(string $query): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT
            id,
            name,
            similarity(normalized_name, :query) AS score
        FROM ingredient
        WHERE similarity(normalized_name, :query) >= :threshold
        ORDER BY score DESC
        LIMIT 5
    ';

        return $connection->executeQuery($sql, [
            'query' => mb_strtolower(trim($query), 'UTF-8'),
            'threshold' => 0.3,
        ])->fetchAllAssociative();
    }
}
