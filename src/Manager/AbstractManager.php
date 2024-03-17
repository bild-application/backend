<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function is_string;

abstract class AbstractManager
{
    protected ?User $user = null;

    /**
     * @throws NonUniqueResultException
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
        $token = $this->tokenStorage->getToken();

        /* @phpstan-ignore-next-line */
        if ($token && $token->getUser() && !is_string($token->getUser())) {
            /* @phpstan-ignore-next-line */
            $this->user = $token->getUser();
        }
    }
}
