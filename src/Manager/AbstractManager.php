<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class AbstractManager
{
    protected ?User $user = null;

    /**
     * @throws NonUniqueResultException
     */
    public function __construct(
        private readonly TokenStorageInterface  $tokenStorage,
    ) {
        $token = $this->tokenStorage->getToken();

        /* @phpstan-ignore-next-line */
        if ($token && $token->getUser() && !\is_string($token->getUser())) {
            $user = $token->getUser();

            /** @var User $user */
            $this->user = $user;
        }
    }
}
