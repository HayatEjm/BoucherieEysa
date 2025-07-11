<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\AwsS3Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            'page_title' => 'Tous nos Produits',
            'page_subtitle' => 'Découvrez notre sélection complète de viandes de qualité',
        ]);
    }

    // Afficher les détails d'un produit
    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/product_detail.html.twig', [
            'product' => $product,
        ]);    }

    // ROUTES D'ADMINISTRATION - Gestion des produits
    
    // Créer un nouveau produit avec upload d'image
    #[Route('/admin/product/new', name: 'app_product_new')]
    public function new(Request $request, AwsS3Service $awsS3Service): Response
    {
        // Si c'est une requête POST (soumission du formulaire)
        if ($request->isMethod('POST')) {
            return $this->handleProductImageUpload($request, $awsS3Service);
        }
        
        // Sinon, afficher le formulaire de création
        return $this->render('admin/product/new.html.twig');
    }

    // Modifier un produit existant avec upload d'image
    #[Route('/admin/product/{id}/edit', name: 'app_product_edit')]
    public function edit(Product $product, Request $request, AwsS3Service $awsS3Service): Response
    {
        // Si c'est une requête POST (soumission du formulaire)
        if ($request->isMethod('POST')) {
            return $this->handleProductImageUpload($request, $awsS3Service, $product);
        }
        
        // Sinon, afficher le formulaire de modification
        return $this->render('admin/product/edit.html.twig', [
            'product' => $product
        ]);
    }

    // Supprimer un produit (optionnel)
    #[Route('/admin/product/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Product $product): Response
    {
        // TODO: Logique pour supprimer un produit
        return new Response('Suppression de produit - À implémenter');
    }

    /**
     * Gère l'upload d'image produit (logique du cours adaptée)
     * Inspiré de ImageController du cours
     */
    private function handleProductImageUpload(Request $request, AwsS3Service $awsS3Service, ?Product $product = null): Response
    {
        // 1. Récupérer le fichier depuis la requête (comme dans le cours)
        $file = $request->files->get('image');
        
        // 2. Vérifier si fichier existe (comme dans le cours)
        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], 400);
        }

        try {
            // 3. Upload vers S3 avec gestion d'erreurs (comme dans le cours)
            $fileUrl = $awsS3Service->upload($file, 'products');
            
            // 4. Ici vous pourriez sauvegarder le produit en base
            // TODO: Créer/modifier l'entité Product avec $fileUrl
            
            return $this->json([
                'success' => true,
                'fileUrl' => $fileUrl,
                'message' => $product ? 'Produit modifié avec succès' : 'Produit créé avec succès'
            ]);
            
        } catch (\Exception $e) {
            // Gestion d'erreur identique au cours
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Route dédiée à l'upload d'images (cf. cours)
     */
    #[Route('/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request, AwsS3Service $awsS3Service): Response
    {
        $file = $request->files->get('image');
        
        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], 400);
        }

        try {
            $fileUrl = $awsS3Service->upload($file, 'images');
            
            return $this->json([
                'success' => true,
                'fileUrl' => $fileUrl
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
