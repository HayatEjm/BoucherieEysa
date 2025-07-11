<?php

namespace App\Controller;

use App\Service\BannerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CONTRÔLEUR CLICK & COLLECT
 * ===========================
 * Ce contrôleur gère l'affichage de la page d'information
 * sur le service Click & Collect de la boucherie
 * 
 * Route : /click-collect
 * Objectif : Expliquer le fonctionnement du service au client
 */
class ClickCollectController extends AbstractController
{
    /**
     * AFFICHAGE PAGE CLICK & COLLECT
     * ==============================
     * Cette méthode affiche la page d'explication du service
     * Click & Collect avec les 3 étapes : Commander, Acheter, Récupérer
     */
    #[Route('/click-collect', name: 'app_click_collect')]
    public function index(BannerService $bannerService): Response
    {
        // Récupération des données du bandeau pour cette page
        $bannerData = $bannerService->getBannerData('click_collect');
        
        // Rendu de la page avec le template dédié
        return $this->render('click_collect/index.html.twig', [
            'page_title' => 'Click & Collect',
            'page_subtitle' => 'Commandez en ligne, récupérez en magasin',
            'bannerData' => $bannerData,  // Données du bandeau
        ]);
    }
}
