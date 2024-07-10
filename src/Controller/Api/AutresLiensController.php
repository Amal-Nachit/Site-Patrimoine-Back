<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AutresLiensController extends AbstractController
{
    #[Route('/api/autres/liens', name: 'app_api_autres_liens')]
    public function index(): Response
    {
        return $this->render('api/autres_liens/index.html.twig', [
            'controller_name' => 'AutresLiensController',
        ]);
    }
}
