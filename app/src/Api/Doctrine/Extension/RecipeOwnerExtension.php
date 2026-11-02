<?php

namespace App\Api\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Recipe;
use Psr\Log\LoggerInterface;


final class RecipeOwnerExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(
        private Security $security,
        private LoggerInterface $logger,
    ) {}

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if (Recipe::class !== $resourceClass) {
            return;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        } else {
            /** @var \App\Entity\User|null $user */
            $user = $this->security->getUser();
            $queryBuilder->andWhere('o.status = :status')
                ->orWhere('o.author = :user')
                ->setParameter('status', 'published')
                ->setParameter('user', $user);
            return;
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?Operation $operation = null, array $context = []): void
    {
        if (Recipe::class !== $resourceClass) {
            return;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        } else {
            /** @var \App\Entity\User|null $user */
            $user = $this->security->getUser();
            $queryBuilder
                ->andWhere('(o.status = :status OR o.author = :user)')
                ->setParameter('status', 'published')
                ->setParameter('user', $user);
            return;
        }
    }
}
