<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/categories', name: 'admin_categories_')]
class CategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->em->getRepository(Category::class)->findBy([], ['id' => 'DESC']);
        return $this->render('admin/categories/index.html.twig', ['categories' => $categories]);
    }

    #[Route('/add', name: 'add', methods: ['GET','POST'])]
    public function add(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $category = new Category();
            $category->setName($request->request->get('name', ''));
            $category->setDescription($request->request->get('description', ''));
            $category->setImage($request->request->get('image', ''));
            
            // Générer un slug simple basé sur le nom
            $slug = strtolower(str_replace(' ', '-', $request->request->get('name', '')));
            $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
            $category->setSlug($slug);

            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie ajoutée.');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/form.html.twig', [
            'category' => null,
            'action'  => 'add',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET','POST'])]
    public function edit(Category $category, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $category->setName($request->request->get('name', $category->getName()));
            $category->setDescription($request->request->get('description', $category->getDescription()));
            $category->setImage($request->request->get('image', $category->getImage()));
            
            // Mettre à jour le slug
            $slug = strtolower(str_replace(' ', '-', $request->request->get('name', '')));
            $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
            $category->setSlug($slug);

            $this->em->flush();
            $this->addFlash('success', 'Catégorie modifiée.');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/form.html.twig', [
            'category' => $category,
            'action'  => 'edit',
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Category $category): Response
    {
        // Vérifier s'il y a des produits dans cette catégorie
        if ($category->getProducts()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer une catégorie qui contient des produits.');
            return $this->redirectToRoute('admin_categories_index');
        }

        $this->em->remove($category);
        $this->em->flush();
        $this->addFlash('success', 'Catégorie supprimée.');
        return $this->redirectToRoute('admin_categories_index');
    }
}