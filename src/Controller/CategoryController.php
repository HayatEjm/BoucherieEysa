<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// #[Route(path:'/category')]
class CategoryController extends AbstractController
{
    // Afficher la liste des catégories
    #[Route('/categories', name: 'app_category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        
        // Format pour le menu déroulant Vue
        $formattedCategories = array_map(function ($category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'slug' => $category->getSlug()
            ];
        }, $categories);

        return $this->render('category/category_list.html.twig', [
            'categories' => $categories,
            'categoriesJson' => $formattedCategories,
        ]);
    }

    // Afficher les produits d'une catégorie donnée
    #[Route('/categories/{slug}', name: 'app_category_show')]
    public function show(string $slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        
        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }
        
        $products = $category->getProducts();
        
        // Format pour le menu déroulant Vue
        $categories = $categoryRepository->findAll();
        $formattedCategories = array_map(function ($cat) {
            return [
                'id' => $cat->getId(),
                'name' => $cat->getName(),
                'slug' => $cat->getSlug()
            ];
        }, $categories);

        return $this->render('category/category_products.html.twig', [
            'category' => $category,
            'products' => $products,
            'categoriesJson' => $formattedCategories,
        ]);
    }
  

}
