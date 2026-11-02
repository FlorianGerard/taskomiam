<?php

namespace App\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Input\UserRegistrationInput;
use App\Api\Dto\User\UserRegistrationOutput;
use App\Entity\User;
use App\Service\UserPassword;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final class UserRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserPassword $userPassword,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly JWTTokenManagerInterface $jwtTokenManager,
        private readonly ObjectMapperInterface $objectMapper,
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {

        $user = $this->objectMapper->map($data, User::class);
        $user->setRoles(['ROLE_USER']);
        $this->userPassword->hashPassword($user);
        $user->setPlainPassword(null);

        $this->persistProcessor->process(
            $user,
            $operation,
            $uriVariables,
            $context
        );

        $output = $this->objectMapper->map(
            $user,
            UserRegistrationOutput::class
        );

        $output->token = $this->jwtTokenManager->create($user);

        return $output;
    }
}
