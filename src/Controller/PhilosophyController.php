<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhilosophyController extends AbstractController
{
    #[Route('/notre-philosophie', name: 'app_philosophy')]
    public function index(): Response
    {
        // Données pour le bandeau de la page
        $bannerData = [
            'title' => 'Notre Philosophie',
            'subtitle' => 'L\'art de la boucherie traditionnelle au service de la qualité',
        ];

        return $this->render('philosophy/index.html.twig', [
            'bannerData' => $bannerData,
        ]);
    }
}
