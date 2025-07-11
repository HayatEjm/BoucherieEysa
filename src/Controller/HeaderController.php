<?php
// src/Controller/HeaderController.php
namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeaderController extends AbstractController
{
    #[Route('/_header', name: 'app_header')]
    public function menu(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('partials/header.html.twig', [
            'categories' => $categories,
        ]);
    }
}

