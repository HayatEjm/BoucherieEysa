<?php

namespace App\Controller;

use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Form\PasswordResetRequestType;
use App\Form\PasswordResetType;
use App\Repository\PasswordResetTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordResetController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/mot-de-passe-oublie', name: 'password_reset_request')]
    public function request(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneByEmail($email);

            if ($user) {
                // Supprimer les anciens tokens de cet utilisateur
                $existingTokens = $this->entityManager->getRepository(PasswordResetToken::class)
                    ->findBy(['user' => $user, 'isUsed' => false]);
                
                foreach ($existingTokens as $token) {
                    $this->entityManager->remove($token);
                }

                // Créer un nouveau token
                $resetToken = new PasswordResetToken();
                $resetToken->setUser($user);
                
                $this->entityManager->persist($resetToken);
                $this->entityManager->flush();

                // Envoyer l'email
                $resetUrl = $this->generateUrl('password_reset_confirm', [
                    'token' => $resetToken->getToken()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new TemplatedEmail())
                    ->from('contact@boucherie-eysa.fr')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe - Boucherie Eysa')
                    ->htmlTemplate('emails/password_reset.html.twig')
                    ->context([
                        'user' => $user,
                        'resetUrl' => $resetUrl,
                        'expiresAt' => $resetToken->getExpiresAt()
                    ]);

                $this->mailer->send($email);
            }

            // Toujours afficher le message de succès pour éviter la divulgation d'informations
            $this->addFlash('success', 'Si cette adresse email existe dans notre système, vous recevrez un lien de réinitialisation.');
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/password_reset_request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/reinitialiser-mot-de-passe/{token}', name: 'password_reset_confirm')]
    public function confirm(
        string $token, 
        Request $request, 
        PasswordResetTokenRepository $tokenRepository
    ): Response {
        $resetToken = $tokenRepository->findValidTokenByToken($token);

        if (!$resetToken || !$resetToken->isValid()) {
            $this->addFlash('error', 'Ce lien de réinitialisation est invalide ou a expiré.');
            return $this->redirectToRoute('password_reset_request');
        }

        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $user = $resetToken->getUser();

            // Hasher le nouveau mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            // Marquer le token comme utilisé
            $resetToken->setIsUsed(true);

            $this->entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/password_reset_confirm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}