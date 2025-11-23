<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(
    name: 'app:generate-slugs',
    description: 'Génère les slugs pour les catégories et produits existants',
)]
class GenerateSlugsCommand extends Command
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Générer slugs pour les catégories
        $categories = $this->categoryRepository->findAll();
        $categoryCount = 0;

        foreach ($categories as $category) {
            if (!$category->getSlug() && $category->getName()) {
                $slug = $this->slugger->slug($category->getName())->lower()->toString();
                $category->setSlug($slug);
                $categoryCount++;
            }
        }

        // Générer slugs pour les produits
        $products = $this->productRepository->findAll();
        $productCount = 0;

        foreach ($products as $product) {
            if (!$product->getSlug() && $product->getName()) {
                $slug = $this->slugger->slug($product->getName())->lower()->toString();
                $product->setSlug($slug);
                $productCount++;
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf(
            'Slugs générés avec succès ! Catégories : %d, Produits : %d',
            $categoryCount,
            $productCount
        ));

        return Command::SUCCESS;
    }
}
