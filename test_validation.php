<?php
// Script temporaire pour tester la validation du minWeight

require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->bootEnv(__DIR__.'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();
$container = $kernel->getContainer();

// Récupération des services
$entityManager = $container->get('doctrine')->getManager();
$cartService = $container->get('App\Service\CartService');

echo "=== TEST DE VALIDATION DU MINWEIGHT ===\n\n";

// Récupérer un produit avec minWeight
$productRepo = $entityManager->getRepository('App\Entity\Product');
$products = $productRepo->findAll();

$testProduct = null;
foreach ($products as $product) {
    if ($product->getMinWeight() !== null) {
        $testProduct = $product;
        break;
    }
}

if (!$testProduct) {
    echo "❌ Aucun produit trouvé avec un minWeight défini\n";
    
    // Créons temporairement un produit pour le test
    $testProduct = $products[0] ?? null;
    if ($testProduct) {
        echo "📝 Modification temporaire du produit '{$testProduct->getName()}' pour le test\n";
        $testProduct->setMinWeight(250); // 250g minimum
        $testProduct->setMaxWeight(2000); // 2kg maximum
        $entityManager->flush();
    } else {
        echo "❌ Aucun produit trouvé dans la base\n";
        exit(1);
    }
}

echo "🧪 Produit de test : {$testProduct->getName()}\n";
echo "⚖️  MinWeight : {$testProduct->getMinWeight()}g\n";
echo "⚖️  MaxWeight : {$testProduct->getMaxWeight()}g\n\n";

// Test 1 : Quantité valide
echo "TEST 1 : Ajout avec quantité valide (500g)\n";
try {
    $cartService->addProduct($testProduct, 500);
    echo "✅ Succès - Produit ajouté\n";
} catch (\Exception $e) {
    echo "❌ Erreur inattendue : " . $e->getMessage() . "\n";
}

// Test 2 : Quantité trop faible
echo "\nTEST 2 : Ajout avec quantité trop faible (100g)\n";
try {
    $cartService->addProduct($testProduct, 100);
    echo "❌ Erreur - La validation n'a pas fonctionné !\n";
} catch (\InvalidArgumentException $e) {
    echo "✅ Succès - Validation fonctionnelle : " . $e->getMessage() . "\n";
}

// Test 3 : Quantité trop élevée (si maxWeight défini)
if ($testProduct->getMaxWeight()) {
    echo "\nTEST 3 : Ajout avec quantité trop élevée (3000g)\n";
    try {
        $cartService->addProduct($testProduct, 3000);
        echo "❌ Erreur - La validation du maximum n'a pas fonctionné !\n";
    } catch (\InvalidArgumentException $e) {
        echo "✅ Succès - Validation du maximum fonctionnelle : " . $e->getMessage() . "\n";
    }
}

echo "\n=== TESTS TERMINÉS ===\n";
