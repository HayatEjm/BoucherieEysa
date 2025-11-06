<?php
// Page d'accueil de l'admin (tableau de bord simple)

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('', name: 'dashboard', methods: ['GET'])]
    public function index(): Response
    {
        // Repos
        $orderRepo   = $this->em->getRepository(Order::class);
        $productRepo = $this->em->getRepository(Product::class);
        $userRepo    = $this->em->getRepository(User::class);

        // Données basiques
        $orders   = $orderRepo->findBy([], ['id' => 'DESC']);
        $products = $productRepo->findAll();
        $users    = $userRepo->findAll();

        // KPI du jour
        $today = (new \DateTimeImmutable('today'))->format('Y-m-d');
        $ordersToday  = 0;
        $revenueToday = 0.0;
        foreach ($orders as $o) {
            if ($o->getCreatedAt()?->format('Y-m-d') === $today) {
                $ordersToday++;
                // Utilisation de la méthode getTotalTtc() qui existe
                $revenueToday += $o->getTotalTtc() ?? 0;
            }
        }

        // Stock faible (<= 5)
        $lowStock = array_values(array_filter($products, fn($p) => ($p->getStock() ?? 0) <= 5));

        return $this->render('admin/dashboard/index.html.twig', [
            'orders_today'       => $ordersToday,
            'total_users'        => count($users),
            'total_products'     => count($products),
            'total_revenue'      => $revenueToday,
            'low_stock_count'    => count($lowStock),
            'low_stock_products' => array_slice($lowStock, 0, 10),
            'recent_orders'      => array_slice($orders, 0, 5),
        ]);
    }
    
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function test(): Response
    {
        return $this->render('admin/test.html.twig');
    }
}
