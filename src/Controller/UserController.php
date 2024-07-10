<?php
 
 namespace App\Controller;
 
 use App\Entity\User;
 use App\Form\UserType;
 use App\Repository\UserRepository;
 use Doctrine\ORM\EntityManagerInterface;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateur', name: 'app_user_')]
class UserController extends AbstractController
 {
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('s', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
     {
         return $this->render('user/index.html.twig', [
             'users' => $userRepository->findAll(),
         ]);
     }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
     {
         $user = new User();
         $form = $this->createForm(UserType::class, $user);
         $form->handleRequest($request);
        
         if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('password')->getData()));
             $entityManager->persist($user);
             $entityManager->flush();
 
             return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
         }
 
         return $this->render('user/new.html.twig', [
             'user' => $user,
             'form' => $form,
         ]);
     }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
     {
         return $this->render('user/show.html.twig', [
             'user' => $user,
         ]);
     }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
     {
         $form = $this->createForm(UserType::class, $user);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('password')->getData()));
            }
             $entityManager->flush();
 
             return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
         }
 
         return $this->render('user/edit.html.twig', [
             'user' => $user,
             'form' => $form,
         ]);
     }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
     {
         if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
             $entityManager->remove($user);
             $entityManager->flush();
         }
 
         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
     }
 }
 
