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
                'name' => $category->getName()
            ];
        }, $categories);

        return $this->render('category/category_list.html.twig', [
            'categories' => $categories,
            'categoriesJson' => $formattedCategories,
        ]);
    }

    // Afficher les produits d'une catégorie donnée
    #[Route('/categories/{id}', name: 'app_category_show')]
    public function show(Category $category, CategoryRepository $categoryRepository): Response
    {
        $products = $category->getProducts();
        
        // Format pour le menu déroulant Vue
        $categories = $categoryRepository->findAll();
        $formattedCategories = array_map(function ($cat) {
            return [
                'id' => $cat->getId(),
                'name' => $cat->getName()
            ];
        }, $categories);

        return $this->render('category/category_products.html.twig', [
            'category' => $category,
            'products' => $products,
            'categoriesJson' => $formattedCategories,
        ]);
    }
  

}
