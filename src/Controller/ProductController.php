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
    // Redirige toute tentative d'accès à /products vers la page d'accueil ou une autre page pertinente
    #[Route('/products', name: 'app_products')]
    public function index(): Response
    {
        // Redirection vers la page d'accueil ou une autre page de ton choix
        return $this->redirectToRoute('app_home');
    }

    // Afficher les détails d'un produit
#[Route('/product/{slug}', name: 'app_product_show')]
public function show(string $slug, ProductRepository $productRepository): Response
{
    $product = $productRepository->findOneBy(['slug' => $slug]);
    
    if (!$product) {
        throw $this->createNotFoundException('Produit introuvable');
    }
    
    $minWeight = $product->getMinWeight() ?? 100; // fallback si null
    $maxWeight = $product->getMaxWeight() ?? 5000;

    $productArray = [
        'id' => $product->getId(),
        'name' => $product->getName(),
        'description' => $product->getDescription(),
        'price' => $product->getPrice(),
        'pricePerKg' => $product->getPrice(),
        'minWeight' => $minWeight,
        'maxWeight' => $maxWeight,
        'image' => $product->getImage(),
        'category' => $product->getCategory() ? [
            'id' => $product->getCategory()->getId(),
            'name' => $product->getCategory()->getName(),
            'slug' => $product->getCategory()->getSlug(),
        ] : null,
    ];

    return $this->render('product/product_detail.html.twig', [
        'product' => $productArray,
    ]);
}


    // ROUTES D'ADMINISTRATION - Gestion des produits

    /**
     * CRÉER (CREATE) - Créer un nouveau produit
     */
    #[Route('/admin/product/new', name: 'app_product_new')]
    public function new(Request $request, AwsS3Service $awsS3Service): Response
    {
        // 1. Créer une nouvelle instance de l'entité Product
        $product = new Product();

        // 2. Créer le formulaire associé à cette entité
        $form = $this->createForm(\App\Form\ProductType::class, $product);

        // 3. Gérer la soumission du formulaire
        $form->handleRequest($request);

        // 4. Vérifier si le formulaire est soumis ET valide
        if ($form->isSubmitted() && $form->isValid()) {
            // 5. Récupérer le fichier image si présent
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                // 6. Upload vers S3 et récupérer l'URL
                $imageUrl = $awsS3Service->upload($imageFile, 'products');
                $product->setImage($imageUrl);
            }

            // 7. Persister l'entité en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);  // Prépare l'insertion
            $entityManager->flush();             // Exécute l'INSERT SQL

            // 8. Message de succès et redirection
            $this->addFlash('success', 'Produit créé avec succès !');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        // 9. Afficher le formulaire (GET ou formulaire invalide)
        return $this->render('admin/product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * MODIFIER (UPDATE) - Modifier un produit existant
     */
    #[Route('/admin/product/{id}/edit', name: 'app_product_edit')]
    public function edit(Product $product, Request $request, AwsS3Service $awsS3Service): Response
    {
        // 1. L'entité Product est déjà récupérée automatiquement via {id}
        
        // 2. Créer le formulaire pré-rempli avec les données existantes
        $form = $this->createForm(\App\Form\ProductType::class, $product);

        // 3. Gérer la soumission du formulaire
        $form->handleRequest($request);

        // 4. Vérifier si le formulaire est soumis ET valide
        if ($form->isSubmitted() && $form->isValid()) {
            // 5. Gérer l'upload d'image si une nouvelle image est fournie
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                $imageUrl = $awsS3Service->upload($imageFile, 'products');
                $product->setImage($imageUrl);
            }

            // 6. Pas besoin de persist() car l'entité existe déjà
            //    Juste flush() pour sauvegarder les modifications
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();  // Exécute l'UPDATE SQL

            // 7. Message de succès et redirection
            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        // 8. Afficher le formulaire avec les données actuelles
        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * SUPPRIMER (DELETE) - Supprimer un produit
     */
    #[Route('/admin/product/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Product $product, Request $request): Response
    {
        // 1. L'entité Product est déjà récupérée automatiquement via {id}

        // 2. Vérifier le token CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            // 3. Supprimer l'entité de la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);  // Prépare la suppression
            $entityManager->flush();            // Exécute le DELETE SQL

            // 4. Message de succès
            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        // 5. Rediriger vers la liste des produits (ou page d'accueil)
        return $this->redirectToRoute('app_home');
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
