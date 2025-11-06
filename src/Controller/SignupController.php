<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignupForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Controller pour l'inscription des nouveaux utilisateurs
 * 
 * Ici je gère tout ce qui concerne la création de nouveaux comptes :
 * - Affichage du formulaire d'inscription
 * - Validation des données
 * - Création du compte en base de données
 * - Envoi d'email de confirmation (optionnel)
 */
final class SignupController extends AbstractController
{
    /**
     * PAGE D'INSCRIPTION
     * 
     * Ce que fait cette méthode :
     * 1. J'affiche le formulaire d'inscription
     * 2. Je traite les données soumises
     * 3. Je crée le compte si tout est correct
     * 4. Je redirige vers la page de connexion
     * 
     * ROUTE : GET/POST /signup
     */
    #[Route('/signup', name: 'app_signup')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        // Si l'utilisateur est déjà connecté, je le redirige vers son compte
        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        // Je crée un nouvel utilisateur vide
        $user = new User();
        
        // Je crée le formulaire et je l'associe à l'utilisateur
        $form = $this->createForm(SignupForm::class, $user);
        
        // Je synchronise le formulaire avec les données de la requête
        // Si c'est un POST, les données sont automatiquement mappées sur $user
        $form->handleRequest($request);
        
        // Si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // ÉTAPE 1 : J'assigne le rôle utilisateur par défaut
                $user->setRoles(['ROLE_USER']);
                
                // ÉTAPE 2 : Je hashe le mot de passe pour la sécurité
                // Le mot de passe en clair ne sera jamais stocké en base
                $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
                
                // ÉTAPE 3 : Je sauvegarde l'utilisateur en base de données
                $em->persist($user); // Prépare l'insertion
                $em->flush();        // Exécute l'insertion
                
                // ÉTAPE 3 : J'envoie un email de bienvenue (optionnel)
                try {
                    $email = new Email();
                    $email
                        ->to($user->getEmail())
                        ->subject('Bienvenue chez Boucherie Eysa !')
                        ->html('<p>Votre compte a été créé avec succès. Bienvenue !</p>');
                    
                    $mailer->send($email);
                } catch (\Exception $e) {
                    // Si l'email échoue, ce n'est pas grave, l'inscription est valide
                    // En production, on pourrait logger cette erreur
                }
                
                // ÉTAPE 4 : Je confirme la création du compte
                $this->addFlash('success', 'Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.');
                
                // ÉTAPE 5 : Je redirige vers la page de connexion
                return $this->redirectToRoute('app_login');
                
            } catch (\Exception $e) {
                // En cas d'erreur (email déjà utilisé, etc.)
                $this->addFlash('error', 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.');
            }
        }
        
        // Si le formulaire n'est pas soumis ou contient des erreurs,
        // j'affiche la page d'inscription avec le formulaire
        return $this->render('security/register.html.twig', [
            'signupForm' => $form->createView()
        ]);
    }
}