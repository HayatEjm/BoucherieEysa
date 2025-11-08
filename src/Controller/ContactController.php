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
use Psr\Log\LoggerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        \Symfony\Component\Mailer\MailerInterface $mailer,
        LoggerInterface $logger
    ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoi d'un email de confirmation au client
            try {
                $emailClient = (new \Symfony\Component\Mime\Email())
                    ->from('contact@boucherie-eysa.fr')
                    ->to($contact->getEmail())
                    ->subject('Confirmation de votre message')
                    ->html('<p>Merci pour votre message ! Nous vous répondrons rapidement.</p>');
                $mailer->send($emailClient);
            } catch (\Throwable $e) {
                $logger->error('Echec envoi email confirmation contact', [
                    'contactId' => method_exists($contact, 'getId') ? $contact->getId() : null,
                    'exception' => $e->getMessage(),
                ]);
            }

            // Envoi d'un email de notification à l'admin
            try {
                $emailAdmin = (new \Symfony\Component\Mime\Email())
                    ->from('contact@boucherie-eysa.fr')
                    ->replyTo($contact->getEmail())
                    ->to('eysa.boucherie@gmail.com')
                    ->subject('Nouveau message de contact sur Boucherie Eysa')
                    ->html('<p><strong>Nom :</strong> ' . $contact->getName() . '<br>' .
                           '<strong>Email :</strong> ' . $contact->getEmail() . '<br>' .
                           '<strong>Téléphone :</strong> ' . $contact->getPhone() . '<br>' .
                           '<strong>Sujet :</strong> ' . $contact->getSubject() . '<br>' .
                           '<strong>Message :</strong><br>' . nl2br($contact->getMessage()) . '</p>');
                $mailer->send($emailAdmin);
            } catch (\Throwable $e) {
                $logger->error('Echec envoi email admin contact', [
                    'contactId' => method_exists($contact, 'getId') ? $contact->getId() : null,
                    'exception' => $e->getMessage(),
                ]);
            }

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
