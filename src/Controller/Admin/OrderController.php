<?php
// Liste des commandes + changement de statut + export CSV + détail

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/orders', name: 'admin_orders_')]
class OrderController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findBy([], ['id' => 'DESC']);
        return $this->render('admin/orders/index.html.twig', ['orders' => $orders]);
    }

    #[Route('/{id}', name: 'view', methods: ['GET'], requirements: ['id' => '\\d+'])]
    public function view(Order $order): Response
    {
        return $this->render('admin/orders/view.html.twig', ['order' => $order]);
    }

    #[Route('/{id}/status', name: 'status', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function updateStatus(Order $order, Request $request): Response
    {
        // Simple formulaire POST (pas d'AJAX ici)
        $new = $request->request->get('status');
        $valid = ['pending','confirmed','ready','completed','cancelled'];

        if (!$new || !in_array($new, $valid, true)) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('admin_orders_index');
        }

        $order->setStatus($new);
        $this->em->flush();
        $this->addFlash('success', 'Statut mis à jour.');

        return $this->redirectToRoute('admin_orders_index');
    }

    #[Route('/export', name: 'export', methods: ['GET'])]
    public function export(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findBy([], ['id' => 'DESC']);

        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, ['ID', 'N° commande', 'N° retrait', 'Date', 'Client', 'Email', 'Téléphone', 'Statut', 'Retrait (date)', 'Créneau', 'Total TTC (€)'], ';');

        foreach ($orders as $o) {
            fputcsv($fh, [
                $o->getId(),
                $o->getOrderNumber(),
                $o->getPickupNumber() ?? '-',
                $o->getCreatedAt()?->format('Y-m-d H:i'),
                $o->getCustomerName(),
                $o->getCustomerEmail(),
                $o->getCustomerPhone(),
                $o->getStatus(),
                $o->getPickupDate()?->format('Y-m-d'),
                $o->getPickupTimeSlot(),
                number_format($o->getTotalTtc(), 2, ',', ' '),
            ], ';');
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        // Compat Excel Windows: ajoute le BOM UTF-8 et force les fins de ligne CRLF
        $csvWithBom = "\xEF\xBB\xBF" . str_replace("\n", "\r\n", $csv);
        $date = (new \DateTime())->format('Y-m-d');
        $filename = sprintf('orders-%s.csv', $date);

        return new Response($csvWithBom, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }
}
