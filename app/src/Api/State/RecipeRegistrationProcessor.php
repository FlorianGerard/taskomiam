<?php

namespace App\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Input\RecipeRegistrationInput;
use App\Api\Dto\Recipe\RecipeRegistrationOutput;

final class RecipeRegistrationProcessor implements ProcessorInterface
{
    public function __construct() {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): RecipeRegistrationOutput
    {
        return new RecipeRegistrationOutput();
    }
}
