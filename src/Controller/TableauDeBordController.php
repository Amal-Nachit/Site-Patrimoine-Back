<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TableauDeBordController extends AbstractController
{
    #[Route('/tableau/de/bord', name: 'app_tableau_de_bord')]
    // #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('tableau_de_bord/index.html.twig', [
            'user' => $user,
            'controller_name' => 'TableauDeBordController',
        ]);
    }
}

