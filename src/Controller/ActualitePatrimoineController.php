<?php


namespace App\Controller;


use App\Entity\ActualitePatrimoine;
use App\Entity\AutresImages;
use App\Entity\AutresLiens;
use App\Form\ActualitePatrimoineType;
use App\Repository\ActualitePatrimoineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/patrimoine/actualite', name: 'app_actualite_patrimoine_')]
class ActualitePatrimoineController extends AbstractController
{
    #[Route('s', name: 'index', methods: ['GET'])]
    public function index(ActualitePatrimoineRepository $actualitePatrimoineRepository): Response
    {
        return $this->render('actualite_patrimoine/index.html.twig', [
            'actualite_patrimoines' => $actualitePatrimoineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ManagerRegistry $mr, #[Autowire('images_directory')] string $images_directory): Response
    {
        $actualitePatrimoine = new ActualitePatrimoine();
        $actualitePatrimoine->setDatePublication(new \DateTime());
        $form = $this->createForm(ActualitePatrimoineType::class, $actualitePatrimoine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actualitePatrimoine = $form->getData();
            // On enregistre l'image principale
            if ($imageActualite = $form['imageActualite']->getData()) {
                $fileName = uniqid() . '.' . $imageActualite->guessExtension();
                $imageActualite->move($this->getParameter($images_directory), $fileName);
                $actualitePatrimoine->setImageActualite($fileName);
            }
            $em->persist($actualitePatrimoine);
            $em->flush();

            // On enregistre les autres images
            $images = $form->get('imageName')->getData();
            foreach ($images as $image) {
                $fichier = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter($images_directory), $fichier);
                $img = new AutresImages();
                $img->setImageName($fichier);
                $actualitePatrimoine->addAutresImage($img);
            }

            // On enregistre les autres liens
            $liens = $form->get('lienUrl')->getData();
            if (!is_array($liens)) {
                $liens = [$liens];
            }
            $textes = $form->get('texteLien')->getData();
            if (!is_array($textes)) {
                $textes = [$textes];
            }
            foreach ($liens as $i => $lien) {
                $texte = $textes[$i] ?? null;
                $lienUrl = new AutresLiens();
                $lienUrl->setLienUrl($lien);
                if ($texte) {
                    $lienUrl->setTexteLien($texte);
                }
                $actualitePatrimoine->addAutresLien($lienUrl);
            }

            $em = $mr->getManager();
            $em->persist($actualitePatrimoine);
            $em->flush();

            return $this->redirectToRoute('app_actualite_patrimoine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actualite_patrimoine/new.html.twig', [
            'actualite_patrimoine' => $actualitePatrimoine,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(ActualitePatrimoine $actualitePatrimoine): Response
    {
        return $this->render('actualite_patrimoine/show.html.twig', [
            'actualite_patrimoine' => $actualitePatrimoine,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActualitePatrimoine $actualitePatrimoine, EntityManagerInterface $em, ManagerRegistry $mr, #[Autowire('images_directory')] string $images_directory): Response
    {
        $form = $this->createForm(ActualitePatrimoineType::class, $actualitePatrimoine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprime toutes les images existantes associées à l'actualité
            foreach ($actualitePatrimoine->getAutresImages() as $oldImage) {
                $em->remove($oldImage);
                $em->flush();
            }
            // On récupère les images transmises
            $images = $form->get('imageName')->getData();
            foreach ($images as $image) {
                $fichier = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter($images_directory), $fichier);
                $img = new AutresImages();
                $img->setImageName($fichier);
                $actualitePatrimoine->addAutresImage($img);

            }
            // On supprime toutes les liens existants associés à l'actualité
            foreach ($actualitePatrimoine->getAutresLiens() as $oldLien) {
                $em->remove($oldLien);
                $em->flush();
            }
            // On récupère les liens transmis
            $liens = $form->get('lienUrl')->getData();
            if (!is_array($liens)) {
                $liens = [$liens];
            }
            // On enregistre les liens
            foreach ($liens as $lien) {
                $lienUrl = new AutresLiens();
                $lienUrl->setLienUrl($lien);
                $actualitePatrimoine->addAutresLien($lienUrl);
            }


            $em = $mr->getManager();
            $em->persist($actualitePatrimoine);
            $em->flush();

            return $this->redirectToRoute('app_actualite_patrimoine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actualite_patrimoine/edit.html.twig', [
            'actualite_patrimoine' => $actualitePatrimoine,
            'form' => $form->createView(),        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, ActualitePatrimoine $actualitePatrimoine, EntityManagerInterface $em, ManagerRegistry $mr): Response
    {
        if ($this->isCsrfTokenValid('delete' . $actualitePatrimoine->getId()    , $request->getPayload()->getString('_token'))) {
            $em = $mr->getManager();
            $em->remove($actualitePatrimoine);
            $em->flush();
        }
        return $this->redirectToRoute('app_actualite_patrimoine_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(AutresImages $image, Request $request, ActualitePatrimoine $actualitePatrimoine, EntityManagerInterface $em, ManagerRegistry $mr): Response
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupere le nom de l'image
            $nom = $image->getImageName();
            // On supprime l'image de la bdd
            unlink($this->getParameter('images_directory') . '/' . $nom);
            // On supprime l'enregistrement de la base de donnée
            $em = $mr->getManager();
            $em->remove($image);
            $em->flush();
        

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
            }
    }

    public function tableauDeBord(): Response
    {
        $user = $this->getUser(); // Ou toute autre logique pour obtenir l'utilisateur
        return $this->render('tableauDeBord.html.twig', [
            'user' => $user,
        ]);
    }
}
