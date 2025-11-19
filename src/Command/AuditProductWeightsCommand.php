<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:audit:product-weights',
    description: 'Audit des poids min/max produits (grammes) et détection des incohérences.'
)]
class AuditProductWeightsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('product-id', null, InputOption::VALUE_REQUIRED, 'ID produit à corriger (optionnel)')
            ->addOption('set-max', null, InputOption::VALUE_REQUIRED, 'Définir maxWeight (en grammes) pour --product-id (optionnel)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $productId = $input->getOption('product-id');
        $setMax = $input->getOption('set-max');

        if ($productId !== null && $setMax !== null) {
            $prod = $this->em->getRepository(Product::class)->find((int) $productId);
            if (!$prod) {
                $io->error(sprintf('Produit #%d introuvable.', (int) $productId));
                return Command::FAILURE;
            }
            $newMax = max(0, (int) $setMax);
            $prod->setMaxWeight($newMax);
            $this->em->flush();
            $io->success(sprintf('Produit #%d (%s) : maxWeight mis à %d g.', $prod->getId(), $prod->getName(), $newMax));
            return Command::SUCCESS;
        }

        $repo = $this->em->getRepository(Product::class);
        $products = $repo->findAll();

        $issues = [
            'missingMin' => [],
            'missingMax' => [],
            'maxLessThanMin' => [],
            'suspiciousSmall' => [],
            'suspiciousHuge' => [],
            'unitPieceWithWeights' => [],
        ];

        foreach ($products as $p) {
            $id = $p->getId();
            $name = $p->getName();
            $unit = $p->getUnit();
            $min = $p->getMinWeight();
            $max = $p->getMaxWeight();

            // Manquants
            if ($unit !== 'pièce' && $min === null) { $issues['missingMin'][] = [$id, $name, $unit]; }
            // Max manquant : ce n'est pas bloquant, mais on le signale
            if ($unit !== 'pièce' && $max === null) { $issues['missingMax'][] = [$id, $name, $unit]; }

            // Incohérence
            if ($min !== null && $max !== null && $max < $min) {
                $issues['maxLessThanMin'][] = [$id, $name, $min, $max];
            }

            // Valeurs suspectes (grammes attendus) : très petites ou très grandes
            if ($min !== null && $min > 0 && $min < 50) {
                $issues['suspiciousSmall'][] = [$id, $name, 'min', $min];
            }
            if ($max !== null && $max > 0 && $max < 50) {
                $issues['suspiciousSmall'][] = [$id, $name, 'max', $max];
            }
            if ($max !== null && $max > 20000) { // >20kg
                $issues['suspiciousHuge'][] = [$id, $name, 'max', $max];
            }

            if ($unit === 'pièce' && (($min ?? 0) > 0 || ($max ?? 0) > 0)) {
                $issues['unitPieceWithWeights'][] = [$id, $name, $min, $max];
            }
        }

        $io->title('Audit des poids produits (en grammes)');

        $print = function(string $label, array $rows) use ($io) {
            if (empty($rows)) {
                $io->writeln("- $label: OK");
                return;
            }
            $io->section($label);
            $io->table(['ID','Nom','Info1','Info2'], array_map(function($r){
                return array_pad($r, 4, '');
            }, $rows));
        };

        $print('Min manquant (unit ≠ pièce)', $issues['missingMin']);
        $print('Max manquant (informative)', $issues['missingMax']);
        $print('Incohérence: max < min', $issues['maxLessThanMin']);
        $print('Valeurs suspectes (< 50g)', $issues['suspiciousSmall']);
        $print('Valeurs suspectes (> 20kg)', $issues['suspiciousHuge']);
        $print('Produits à l\'unité avec poids renseignés', $issues['unitPieceWithWeights']);

        $io->newLine();
        $io->text('Conseils:');
        $io->listing([
            'Les poids sont stockés en grammes. Vérifiez les valeurs très petites (<50) ou très grandes (>20 000).',
            'Pour corriger un produit ciblé: --product-id=ID --set-max=GRAMMES (ex: 3000 pour 3kg).',
            'Pour les produits vendus à la pièce, laissez min/max vides (null).',
        ]);

        return Command::SUCCESS;
    }
}
