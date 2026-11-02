<?php

namespace App\Api\State;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ApiPlatform\Metadata\Operation;
use App\Entity\User;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Dto\User\UserUpdateOutput;
use App\Repository\UserRepository;
use App\Service\UserPassword;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final class UserUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserPassword $userPassword,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly ObjectMapperInterface $objectMapper,
        private readonly UserRepository $userRepository,
        private readonly JWTTokenManagerInterface $jwtTokenManager,
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        $generateToken = false;
        $user = $this->userRepository->find($uriVariables['id']);

        if ($user instanceof User === false) {
            throw new NotFoundHttpException('User not found');
        }

        if (isset($data->email)) {
            $data->email = trim(strtolower($data->email));
            if ($data->email !== $user->getEmail()) {
                $generateToken = true;
            }
        }

        $this->objectMapper->map($data, $user);

        if ($user->getPlainPassword() !== null && $user->getPlainPassword() !== '') {
            $this->userPassword->hashPassword($user);
            $user->setPlainPassword(null);
        }

        $this->persistProcessor->process(
            $user,
            $operation,
            $uriVariables,
            $context
        );

        if ($generateToken) {
            $newtoken = $this->jwtTokenManager->create($user);
        }

        $output = $this->objectMapper->map(
            $user,
            UserUpdateOutput::class
        );

        if (isset($newtoken)) {
            $output->token = $newtoken;
        }

        return $output;
    }
}
