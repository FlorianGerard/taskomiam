<?php

namespace App\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Api\Input\UserUpdateInput;
use App\Api\State\UserUpdateProcessor;
use App\Api\Dto\User\UserUpdateOutput;
use App\Api\Input\UserRegistrationInput;
use App\Api\Dto\User\UserRegistrationOutput;
use App\Api\State\UserRegistrationProcessor;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Doctrine\Orm\State\Options;
use App\Entity\User as UserEntity;

#[ApiResource(
    stateOptions: new Options(entityClass: UserEntity::class),
    operations: [
        new Post(
            input: UserRegistrationInput::class,
            output: UserRegistrationOutput::class,
            processor: UserRegistrationProcessor::class,
        ),
        new Patch(
            input: UserUpdateInput::class,
            output: UserUpdateOutput::class,
            processor: UserUpdateProcessor::class,
            security: "is_granted('ROLE_ADMIN') or (user != null and object.getId() == user.getId())"
        )
    ]
)]
final class User
{
    #[ApiProperty(identifier: true)]
    public ?int $id = null;
}
