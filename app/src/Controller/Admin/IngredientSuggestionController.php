<?php

namespace App\Controller\Admin;

use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientSuggestionController extends AbstractController
{
    #[Route(
        '/admin/ingredients/suggestions/{query}',
        name: 'admin_ingredient_suggestions',
        methods: ['GET']
    )]
    public function suggestions(
        IngredientRepository $repository,
        string $query,
    ): JsonResponse {
        $ingredients = $repository->findSimilarNames($query);

        return $this->json($ingredients, 200, [], [
            'groups' => ['ingredient:read']
        ]);
    }
}
