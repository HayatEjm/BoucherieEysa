<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/legal', name: 'legal_')]
class LegalController extends AbstractController
{
    #[Route('/mentions-legales', name: 'mentions', methods: ['GET'])]
    public function mentionsLegales(): Response
    {
        return $this->render('legal/mentions_simple.html.twig');
    }

    #[Route('/politique-confidentialite', name: 'privacy', methods: ['GET'])]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('legal/politique_simple.html.twig');
    }

    #[Route('/conditions-generales-vente', name: 'cgv', methods: ['GET'])]
    public function conditionsGeneralesVente(): Response
    {
        return $this->render('legal/cgv_simple.html.twig');
    }
}