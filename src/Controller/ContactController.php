<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $entityManager->persist($contact);
            $entityManager->flush();
            
            // Message de confirmation
            $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            
            // Redirection pour éviter la resoumission du formulaire
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Contactez-nous',
            'meta_description' => 'Contactez la Boucherie Eysa à Saumur. Formulaire de contact pour vos questions, commandes spéciales et informations sur nos produits de qualité.'
        ]);
    }

    /**
     * Page pour afficher les messages (pour l'admin - optionnel)
     */
    #[Route('/admin/contact/messages', name: 'app_contact_messages')]
    public function messages(ContactRepository $contactRepository): Response
    {
        $messages = $contactRepository->findBy([], ['createdAt' => 'DESC']);
        
        return $this->render('contact/messages.html.twig', [
            'messages' => $messages,
        ]);
    }
}
