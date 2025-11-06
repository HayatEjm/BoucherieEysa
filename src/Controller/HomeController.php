<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * CONTROLLER HOME - Je gère la page d'accueil
 * 
 * POURQUOI CE CONTROLLER ?
 * - Je affiche la page d'accueil avec les sections principales
 * - Je récupère les catégories et produits vedettes pour l'affichage
 * - Je centralise l'accès aux informations principales du site
 */
class HomeController extends AbstractController
{
    /**
     * Page d'accueil principale
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
        $categories = $categoryRepository->findAll();

        // On transforme les catégories en tableau simple pour Vue
        $formattedCategories = array_map(function ($category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName()
            ];
        }, $categories);

        $featuredProducts = $productRepository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'categories' => $categories, // Pour Twig
            'categoriesJson' => $formattedCategories, // pour Vue
            'featuredProducts' => $featuredProducts,
            
        ]);
    }
    #[Route('/test-404')]
public function test404(): never {
    throw new NotFoundHttpException('Test 404');
}

#[Route('/test-403')]
public function test403(): never {
    throw new AccessDeniedException('Test 403');
}
}