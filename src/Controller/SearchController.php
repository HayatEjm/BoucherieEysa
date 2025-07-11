<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function search(
        Request $request, 
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): JsonResponse {
        $query = $request->query->get('q', '');
        
        if (strlen($query) < 2) {
            return new JsonResponse([
                'products' => [],
                'categories' => [],
                'message' => 'Tapez au moins 2 caractères pour rechercher'
            ]);
        }
        
        // Recherche dans les produits
        $products = $productRepository->searchByName($query, 6); // Limite à 6 résultats
        
        // Recherche dans les catégories
        $categories = $categoryRepository->searchByName($query, 3); // Limite à 3 résultats
        
        // Formatage des résultats pour JSON
        $productsData = array_map(function($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'unit' => $product->getUnit(),
                'image' => $product->getImage(),
                'category_name' => $product->getCategory() ? $product->getCategory()->getName() : 'Non catégorisé',
                'url' => $this->generateUrl('app_product_show', ['id' => $product->getId()])
            ];
        }, $products);
        
        $categoriesData = array_map(function($category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'product_count' => $category->getProducts()->count(),
                'url' => $this->generateUrl('app_category_show', ['id' => $category->getId()])
            ];
        }, $categories);
        
        return new JsonResponse([
            'products' => $productsData,
            'categories' => $categoriesData,
            'total_results' => count($productsData) + count($categoriesData)
        ]);
    }
}
