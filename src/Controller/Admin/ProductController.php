<?php
// Liste produits + créer/éditer/supprimer + mise à jour stock (formulaire simple)

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/products', name: 'admin_products_')]
class ProductController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->em->getRepository(Product::class)->findBy([], ['id' => 'DESC']);
        return $this->render('admin/products/index.html.twig', ['products' => $products]);
    }

    #[Route('/add', name: 'add', methods: ['GET','POST'])]
    public function add(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $p = new Product();
            // Champs principaux (simples)
            $p->setName($request->request->get('name', ''));
            $p->setDescription($request->request->get('description', ''));
            $p->setPrice((float) $request->request->get('price', 0));
            $p->setStock((int) $request->request->get('stock', 0));
            $p->setImage($request->request->get('image', ''));
            $p->setUnit($request->request->get('unit', 'kg'));

            // Limites de poids (en grammes) - facultatives
            $minWeightRaw = trim((string) $request->request->get('min_weight', ''));
            $maxWeightRaw = trim((string) $request->request->get('max_weight', ''));

            $minWeight = ($minWeightRaw === '') ? null : max(0, (int) $minWeightRaw);
            $maxWeight = ($maxWeightRaw === '') ? null : max(0, (int) $maxWeightRaw);

            // Validation simple cohérence min/max
            if ($minWeight !== null && $maxWeight !== null && $maxWeight < $minWeight) {
                $this->addFlash('error', 'Le poids maximum doit être supérieur ou égal au poids minimum.');
                // Re-render formulaire avec valeurs saisies
                $categories = $this->em->getRepository(Category::class)->findAll();
                return $this->render('admin/products/form.html.twig', [
                    'product' => $p,
                    'action'  => 'add',
                    'categories' => $categories,
                ]);
            }

            $p->setMinWeight($minWeight);
            $p->setMaxWeight($maxWeight);
            
            // Gérer la catégorie
            $categoryId = $request->request->get('category_id');
            if ($categoryId) {
                $category = $this->em->getRepository(Category::class)->find($categoryId);
                if ($category) {
                    $p->setCategory($category);
                }
            }

            $this->em->persist($p);
            $this->em->flush();
            $this->addFlash('success', 'Produit ajouté.');
            return $this->redirectToRoute('admin_products_index');
        }

        // Récupérer toutes les catégories pour le formulaire
        $categories = $this->em->getRepository(Category::class)->findAll();

        return $this->render('admin/products/form.html.twig', [
            'product' => null,
            'action'  => 'add',
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET','POST'])]
    public function edit(Product $product, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $product->setName($request->request->get('name', $product->getName()));
            $product->setDescription($request->request->get('description', $product->getDescription()));
            $product->setPrice((float) $request->request->get('price', $product->getPrice()));
            $product->setStock((int) $request->request->get('stock', $product->getStock()));
            $product->setImage($request->request->get('image', $product->getImage()));
            $product->setUnit($request->request->get('unit', $product->getUnit() ?? 'kg'));

            // Limites de poids (en grammes) - facultatives
            $minWeightRaw = trim((string) $request->request->get('min_weight', ''));
            $maxWeightRaw = trim((string) $request->request->get('max_weight', ''));

            $minWeight = ($minWeightRaw === '') ? null : max(0, (int) $minWeightRaw);
            $maxWeight = ($maxWeightRaw === '') ? null : max(0, (int) $maxWeightRaw);

            if ($minWeight !== null && $maxWeight !== null && $maxWeight < $minWeight) {
                $this->addFlash('error', 'Le poids maximum doit être supérieur ou égal au poids minimum.');
                $categories = $this->em->getRepository(Category::class)->findAll();
                return $this->render('admin/products/form.html.twig', [
                    'product' => $product,
                    'action'  => 'edit',
                    'categories' => $categories,
                ]);
            }

            $product->setMinWeight($minWeight);
            $product->setMaxWeight($maxWeight);
            
            // Gérer la catégorie
            $categoryId = $request->request->get('category_id');
            if ($categoryId) {
                $category = $this->em->getRepository(Category::class)->find($categoryId);
                $product->setCategory($category);
            } else {
                $product->setCategory(null);
            }

            $this->em->flush();
            $this->addFlash('success', 'Produit modifié.');
            return $this->redirectToRoute('admin_products_index');
        }

        // Récupérer toutes les catégories pour le formulaire
        $categories = $this->em->getRepository(Category::class)->findAll();

        return $this->render('admin/products/form.html.twig', [
            'product' => $product,
            'action'  => 'edit',
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/stock', name: 'stock', methods: ['POST'])]
    public function stock(Product $product, Request $request): Response
    {
        $newStock = (int) $request->request->get('stock', $product->getStock());
        if ($newStock < 0) {
            $this->addFlash('error', 'Stock invalide.');
            return $this->redirectToRoute('admin_products_index');
        }
        $product->setStock($newStock);
        $this->em->flush();
        $this->addFlash('success', 'Stock mis à jour.');
        return $this->redirectToRoute('admin_products_index');
    }

    #[Route('/{id}/update-stock', name: 'update_stock', methods: ['POST'])]
    public function updateStock(Product $product, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['stock']) || !is_numeric($data['stock']) || $data['stock'] < 0) {
                return new JsonResponse(['success' => false, 'error' => 'Stock invalide'], 400);
            }
            
            $newStock = (int) $data['stock'];
            $product->setStock($newStock);
            
            // Mise à jour du seuil d'alerte si fourni
            if (isset($data['alertThreshold'])) {
                $threshold = $data['alertThreshold'] === null ? null : (int) $data['alertThreshold'];
                if ($threshold !== null && $threshold < 0) {
                    return new JsonResponse(['success' => false, 'error' => 'Seuil d\'alerte invalide'], 400);
                }
                $product->setAlertThreshold($threshold);
            }
            
            $this->em->flush();
            
            return new JsonResponse([
                'success' => true,
                'stock' => $newStock,
                'status' => $product->getStockStatus()
            ]);
            
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Product $product): Response
    {
        $this->em->remove($product);
        $this->em->flush();
        $this->addFlash('success', 'Produit supprimé.');
        return $this->redirectToRoute('admin_products_index');
    }
}
