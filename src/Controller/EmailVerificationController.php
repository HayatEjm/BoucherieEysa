<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de vérification d'email
 */
class EmailVerificationController extends AbstractController
{
    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(
        string $token,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response {
        // Recherche de l'utilisateur par token
        $user = $userRepository->findOneBy(['verificationToken' => $token]);
        
        if (!$user) {
            $this->addFlash('error', 'Token de vérification invalide ou expiré.');
            return $this->redirectToRoute('app_login');
        }
        
        // Si déjà vérifié
        if ($user->isVerified()) {
            $this->addFlash('info', 'Votre compte est déjà vérifié. Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }
        
        // Activation du compte
        $user->setIsVerified(true);
        $user->setVerificationToken(null); // Suppression du token utilisé
        $em->flush();
        
        $this->addFlash('success', 'Votre compte a été vérifié avec succès ! Vous pouvez maintenant vous connecter.');
        
        return $this->redirectToRoute('app_login');
    }
}
