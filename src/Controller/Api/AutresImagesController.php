<?php

namespace App\Controller\Api;

use App\Entity\ActualitePatrimoine;
use App\Entity\AutresImages;
use App\Repository\AutresImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/autres/images', name: 'api_autres_images')]

class AutresImagesController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AutresImagesRepository $autresImages): JsonResponse
    {
        $images = $autresImages->findAll();
        return $this->json($images, context: ['groups' => 'api_autres_images_index']);

    }

    public function show(AutresImages $images): JsonResponse
    {

        return $this->json($images, context: ['groups' => ['api_autres_images_index', 'api_autres_images_show']]);

    }
}
