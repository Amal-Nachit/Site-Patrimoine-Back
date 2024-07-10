<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgotten_password')]
    public function forgottenPassword(Request $request, 
    UserRepository $userRepository,
    TokenGeneratorInterface $tokenGenerator,
    EntityManagerInterface $entityManager,
    SendMailService $mail): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On cherche l'utilisateur par son email
            $user = $userRepository->findOneByEmail(['email' => $form->get('email')->getData()]);
            // On verifie si l'utilisateur existe
            if (!$user) {
                $this->addFlash('danger', 'Cet email n\'existe pas');
                return $this->redirectToRoute('app_user_index');
            } else {
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                // On enregistre le token de réinitialisation de passe dans la base de données
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
                // On génère un lien de réinitialisation
                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                // On crée les données du mail
                $context = compact('url', 'user');
                // On envoie le mail
                $mail->send(
                    'no-reply@actualite-patrimoine-crolles.fr',
                    $user->getEmail(),
                    'Reinitialisation de votre mot de passe',
                    'password_reset',
                    $context
                );
               $this->addFlash('success', 'Un email de reinitialisation a bien été envoyé, veuillez consulter votre boite mail.');
               return $this->redirectToRoute('app_login');
            }   
            
             // $user est null
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_forgotten_password');
        }
        return $this->render('security/reset_password_request.html.twig', [
            'requestPasswordForm' => $form->createView(),
        ]);
        }
    

    #[Route(path: '/mot-de-passe-oublie/{token}', name: 'app_reset_password')]
    public function resetPassword(EntityManagerInterface $entityManager, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator): Response
    {
        $token = $tokenGenerator->generateToken();
        $user = $userRepository->findOneBy(['resetToken' => $token]);
        if ($user) {
            $user->setResetToken(null);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Mot de passe reinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }
        $this->addFlash('danger', 'Le token de reinitialisation est invalide.');
        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
        ]);
    }
}
