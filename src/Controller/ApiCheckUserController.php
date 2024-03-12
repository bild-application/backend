<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiCheckUserController extends AbstractController
{
    #[Route('/api/check', name: 'api_check', methods: ['GET'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if($user === null) {
            return $this->json([
                'message'=>'missing credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user'  => $user->getUserIdentifier(),
        ]);
    }
}
