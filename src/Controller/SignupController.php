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

final class SignupController extends AbstractController
{
    #[Route('./form/signup', name: 'app-signup')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(SignupForm::class, $user);
        // syncronise avec la requete avec les donnée dans le formulaire pour mapper automatiquement avec user 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            // $em->persist($user); #methode de l'entity manager qui permet de préparer la requête (insert into...)
            // $em->flush(); //executer dans la base de donnée 
            $email= new Email();
            $email
            ->to($user->getEmail())
            ->subject('test email')
            ->html('<p> Ici on test les emails envoyer </p>')
            ;
            $mailer->send($email);
    
            return $this->redirectToRoute('app_login');
        }
        return $this->render('form/signupForm.html.twig', [
            'signupForm' => $form->createView()
        ]);
    }
}