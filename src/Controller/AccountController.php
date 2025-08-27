<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller pour la page "Mon Compte"
 * 
 * Ici je gère tout ce qui concerne l'espace personnel de l'utilisateur :
 * - Affichage des informations du compte
 * - Historique des commandes (prévu pour plus tard)
 * - Liens vers les actions importantes
 * 
 * SÉCURITÉ : Seuls les utilisateurs connectés peuvent accéder à ces pages
 */
class AccountController extends AbstractController
{
    /**
     * PAGE PRINCIPALE "MON COMPTE"
     * 
     * Ce que fait cette méthode :
     * 1. Je vérifie que l'utilisateur est connecté (avec IsGranted)
     * 2. Je récupère ses informations
     * 3. Je les passe au template pour affichage
     * 
     * ROUTE : GET /mon-compte
     * PROTECTION : Il faut être connecté (ROLE_USER)
     */
    #[Route('/mon-compte', name: 'app_account', methods: ['GET'])]
    #[IsGranted('ROLE_USER')] // ← Si pas connecté, redirection automatique vers /login
    public function index(): Response
    {
        // Je récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // $user ne peut pas être null ici car IsGranted vérifie avant
        // Mais c'est une bonne habitude de vérifier quand même
        if (!$user) {
            // Normalement impossible d'arriver ici, mais au cas où
            return $this->redirectToRoute('app_login');
        }
        
        // TODO : Plus tard, je pourrai ajouter d'autres données
        // Exemple : $orders = $orderRepository->findByUser($user);
        
        // Je passe les données au template
        return $this->render('account/index.html.twig', [
            'user' => $user,
            // TODO : 'orders' => $orders,
        ]);
    }
    
    /**
     * PAGE PROFIL - Modification des informations personnelles
     * 
     * Ici l'utilisateur peut modifier :
     * - Son email
     * - Son mot de passe (optionnel)
     * 
     * ROUTE : GET/POST /mon-compte/profil
     * PROTECTION : Il faut être connecté (ROLE_USER)
     */
    #[Route('/mon-compte/profil', name: 'app_account_profile', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function profile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        // Je crée le formulaire et je l'associe à l'utilisateur
        $form = $this->createForm(ProfileForm::class, $user);
        $form->handleRequest($request);
        
        // Si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Si un nouveau mot de passe a été saisi
                $newPassword = $form->get('newPassword')->getData();
                if ($newPassword) {
                    $user->setPassword($hasher->hashPassword($user, $newPassword));
                }
                
                // Je sauvegarde les modifications
                $em->flush();
                
                // Message de confirmation
                $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');
                
                // Redirection vers la page compte
                return $this->redirectToRoute('app_account');
                
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.');
            }
        }
        
        // Affichage du formulaire
        return $this->render('account/profile.html.twig', [
            'user' => $user,
            'profileForm' => $form->createView()
        ]);
    }
}
