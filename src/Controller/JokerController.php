<?php

namespace App\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/')]
class JokerController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
    #[Rest\Get(path: 'joker', name: 'joker')]
    #[Rest\View(statusCode: 200, serializerGroups: ['user'])]
    public function joker(): Response
    {
        $view = $this->view([
            'joker' => 'Hello word !'
        ], 200);

        return $this->handleView($view);
    }

}
