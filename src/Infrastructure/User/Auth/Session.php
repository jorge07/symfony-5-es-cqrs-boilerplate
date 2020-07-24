<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Auth;

use App\Domain\User\Exception\InvalidCredentialsException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class Session
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function get(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new InvalidCredentialsException();
        }

        $user = $token->getUser();
        if (!$user instanceof Auth) {
            throw new InvalidCredentialsException();
        }

        return [
            'uuid' => $user->uuid(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];
    }

    public function sameByUuid(string $uuid): bool
    {
        /** @var Uuid $userUuid */
        $userUuid = $this->get()['uuid'];

        return $userUuid->equals(Uuid::fromString($uuid));
    }
}
