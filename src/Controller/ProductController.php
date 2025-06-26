<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    // Cette méthode est appelée quand on visite l'URL "/products"
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $productRepository): Response
    {
        // On récupère tous les produits via le repository
        $products = $productRepository->findAll();

        // On passe les produits à la vue (fichier Twig)
        return $this->render('product/product_list.html.twig', [
            'products' => $products,
        ]);
    }
}
