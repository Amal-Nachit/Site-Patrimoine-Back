<?php

namespace App\Controller\Api;

use App\Repository\ActualitePatrimoineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/patrimoine/actualite', name: 'api_actualite_patrimoine_')]
class ActualitePatrimoineController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(ActualitePatrimoineRepository $actualitePatrimoineRepository, RequestStack $requestStack): JsonResponse
     {
         $actualites = $actualitePatrimoineRepository->findAll();    
         $data = [];
         foreach ($actualites as $actualite) {
             $imageActualite = $actualite->getImageActualite();
             if ($imageActualite) {
                 $data[] = [
                     'id' => $actualite->getId(),
                     'titre' => $actualite->getTitreActualite(),
                     'date' => $actualite->getDatePublication()->format('d/m/Y'),
                    'image' => $requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/uploads/images/'.$imageActualite,
                     'contenu' => $actualite->getContenuActualite(),
                 ];
             } else {
                 $data[] = [
                     'id' => $actualite->getId(),
                     'titre' => $actualite->getTitreActualite(),
                     'date' => $actualite->getDatePublication()->format('d/m/Y'),
                 ];
             }
         }
         return $this->json($data, context: ['groups' => 'api_actualite_patrimoine_index']);
     }

    #[Route('/{id}', name: 'show')]
       public function show(ActualitePatrimoineRepository $actualitePatrimoineRepository, RequestStack $requestStack, $id): JsonResponse
       {
          $actualite = $actualitePatrimoineRepository->find($id);
          $imageActualite = $actualite->getImageActualite();
          $autresLiens = $actualite->getAutresLiens();
         $data = [
             'id' => $actualite->getId(),
             'titre' => $actualite->getTitreActualite(),
             'date' => $actualite->getDatePublication()->format('d/m/Y'),
         ];
         if ($imageActualite) {
             $data['image'] = $requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/uploads/images/'.$imageActualite;
         }
         $data['contenu'] = $actualite->getContenuActualite();
          $imageName = [];
          foreach ($actualite->getAutresImages() as $image) {
             $imageName[] = $requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/uploads/images/'.$image->getImageName();
          }
         $data['image_name'] = $imageName;
         $data['lien_url'] = [];
         $data['texte_lien'] = [];
         foreach ($autresLiens as $autreLien) {
             $data['lien_url'][] = $autreLien->getLienUrl();
             $data['texte_lien'][] = $autreLien->getTexteLien();
          }
          return $this->json($data, context: ['groups' => ['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show']]);
      }

}