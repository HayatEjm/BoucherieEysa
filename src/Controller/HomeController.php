<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 🏠 CONTROLLER HOME - Je gère la page d'accueil
 * 
 * POURQUOI CE CONTROLLER ?
 * - Je affiche la page d'accueil avec les sections principales
 * - Je récupère les catégories et produits vedettes pour l'affichage
 * - Je centralise l'accès aux informations principales du site
 */
class HomeController extends AbstractController
{
    /**
     * 🏠 Page d'accueil principale
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Point d'entrée principal du site
     * - Affiche les catégories principales
     * - Montre les produits vedettes
     * - Présente les avantages de la boucherie
     */
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        // Je récupère les catégories pour l'affichage sur la page d'accueil
        $categories = $categoryRepository->findAll();
        
        // Je récupère quelques produits vedettes (les 6 premiers par exemple)
        $featuredProducts = $productRepository->findBy([], ['id' => 'DESC'], 6);
        
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
