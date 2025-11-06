<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-categories',
    description: 'Initialise les catégories de base pour la boucherie',
)]
class InitCategoriesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $categories = [
            [
                'name' => 'Bœuf',
                'description' => 'Nos meilleures pièces de bœuf sélectionnées chez nos éleveurs partenaires',
                'image' => 'category-boeuf.jpg',
                'slug' => 'boeuf'
            ],
            [
                'name' => 'Porc',
                'description' => 'Cochons élevés en plein air, une viande tendre et savoureuse',
                'image' => 'category-porc.jpg',
                'slug' => 'porc'
            ],
            [
                'name' => 'Volaille',
                'description' => 'Poulets fermiers et canards élevés dans nos fermes locales',
                'image' => 'category-volaille.jpg',
                'slug' => 'volaille'
            ],
            [
                'name' => 'Agneau',
                'description' => 'Agneau de nos bergers locaux, une viande délicate et parfumée',
                'image' => 'category-agneau.jpg',
                'slug' => 'agneau'
            ],
            [
                'name' => 'Charcuterie',
                'description' => 'Charcuterie artisanale fabriquée sur place selon nos recettes traditionnelles',
                'image' => 'category-charcuterie.jpg',
                'slug' => 'charcuterie'
            ],
            [
                'name' => 'Spécialités',
                'description' => 'Nos spécialités maison et préparations du chef',
                'image' => 'category-specialites.jpg',
                'slug' => 'specialites'
            ]
        ];

        $created = 0;
        $existing = 0;

        foreach ($categories as $categoryData) {
            // Vérifier si la catégorie existe déjà
            $existingCategory = $this->em->getRepository(Category::class)->findOneBy(['name' => $categoryData['name']]);
            
            if ($existingCategory) {
                $existing++;
                $io->text("Catégorie '{$categoryData['name']}' existe déjà - ignorée");
                continue;
            }

            // Créer la nouvelle catégorie
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setDescription($categoryData['description']);
            $category->setImage($categoryData['image']);
            $category->setSlug($categoryData['slug']);

            $this->em->persist($category);
            $created++;
            $io->text("Catégorie '{$categoryData['name']}' créée");
        }

        $this->em->flush();

        $io->success("Initialisation terminée : {$created} catégorie(s) créée(s), {$existing} existante(s)");
        
        if ($created > 0) {
            $io->note([
                'Les catégories ont été créées avec des images par défaut.',
                'Vous pouvez maintenant :',
                '1. Aller dans l\'admin : /admin/categories',
                '2. Modifier les images et descriptions',
                '3. Ajouter vos produits avec une catégorie assignée'
            ]);
        }

        return Command::SUCCESS;
    }
}