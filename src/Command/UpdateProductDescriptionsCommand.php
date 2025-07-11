<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * 📝 COMMANDE POUR AJOUTER LES DESCRIPTIONS PRODUITS
 * 
 * Cette commande met à jour toutes les descriptions des produits
 * avec des textes appétissants et professionnels pour la boucherie.
 */
#[AsCommand(
    name: 'app:update-product-descriptions',
    description: 'Met à jour les descriptions de tous les produits avec des textes appétissants'
)]
class UpdateProductDescriptionsCommand extends Command
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $descriptions = [
            // BŒUF - Morceaux à mijoter
            1 => "Morceaux de bœuf tendres et savoureux, parfaits pour vos bourguignons. Sélectionnés dans le paleron et l'épaule, ils révèlent toute leur richesse après une cuisson lente. Idéal pour 4-6 personnes.",
            2 => "Jarret de bœuf avec os, riche en collagène. Parfait pour vos pot-au-feu et bouillons. La cuisson lente transforme ce morceau en une viande fondante et goûteuse. L'os apporte une saveur authentique.",
            3 => "Plat de côte de bœuf, morceau économique et savoureux. Idéal pour les plats mijotés, pot-au-feu et bouillons. Sa texture gélatineuse après cuisson en fait un favori des cuisiniers traditionnels.",
            4 => "Paleron de bœuf, le roi des morceaux à braiser. Viande persillée qui devient fondante après cuisson lente. Parfait pour les ragoûts, bourguignons et daubes. Un incontournable de la cuisine familiale.",
            5 => "Basse côte de bœuf, morceau charnu et parfumé. Excellent pour les grillades ou en cuisson lente. Sa texture persillée garantit une viande juteuse et savoureuse. Idéal pour les barbecues.",
            
            // BŒUF - Morceaux nobles
            6 => "Rumsteak de bœuf, morceau tendre et savoureux. Parfait grillé ou poêlé, il révèle un goût authentique de bœuf. Cuisson recommandée : saignant à point. Une valeur sûre pour vos repas de fête.",
            7 => "Poire de bœuf, morceau tendre du faux-filet. Viande maigre et savoureuse, idéale pour les grillades et rôtis. Sa forme caractéristique en fait un morceau apprécié des connaisseurs.",
            8 => "Merlan de bœuf, petit muscle tendre et délicieux. Parfait pour les grillades rapides et les poêlées. Sa texture fine en fait un morceau de choix pour les amateurs de viande tendre.",
            9 => "Rôti de bœuf dans la pointe de culotte. Viande tendre et juteuse, parfaite pour vos rôtis dominicaux. Cuisson au four recommandée avec une pointe d'ail et d'herbes de Provence.",
            10 => "Queue de bœuf, morceau traditionnel riche en saveurs. Parfait pour les daubes et ragoûts. Sa richesse en collagène donne des sauces onctueuses et parfumées. Un régal de la cuisine d'antan.",
            11 => "Joue de bœuf, morceau fondant aux saveurs intenses. Idéal pour les plats mijotés et confits. Sa texture devient soyeuse après une cuisson lente. Un favori des grands chefs.",
            
            // BŒUF - Viandes hachées
            12 => "Viande hachée de bœuf fraîche, 15% de matière grasse. Parfaite pour vos bolognaises, hachis Parmentier et boulettes. Hachée finement le jour même pour garantir fraîcheur et saveur.",
            13 => "Steaks hachés de bœuf façonnés à la main. 15% de matière grasse pour un équilibre parfait entre saveur et tenue. Idéaux grillés ou poêlés, nature ou avec vos épices préférées.",
            
            // BŒUF - Pièces nobles
            14 => "Faux-filet de bœuf, morceau tendre et persillé. Parfait grillé, poêlé ou en rôti. Sa texture fondante et son goût authentique en font une pièce de choix pour vos occasions spéciales.",
            15 => "Entrecôte de bœuf, la star des grillades. Viande persillée et savoureuse, tendre et juteuse. Parfaite sur le grill ou à la plancha. Un classique indémodable de la boucherie française.",
            16 => "Côte de bœuf, pièce majestueuse pour vos grandes occasions. Viande persillée et tendre, parfaite rôtie au four. Idéale pour 6-8 personnes. L'os apporte une saveur incomparable.",
            17 => "Bavette d'aloyau, morceau au goût prononcé. Viande fibreuse mais savoureuse, parfaite marinée et grillée. Cuisson saignante recommandée. Appréciée des amateurs de saveurs intenses.",
            18 => "Onglet de bœuf, le morceau du boucher. Texture unique et goût corsé, parfait grillé saignant. Viande tendre aux saveurs authentiques. Un incontournable pour les connaisseurs.",
            19 => "Foie de bœuf frais, riche en fer et vitamines. Parfait poêlé aux oignons ou en terrine. Saveur typée appréciée des amateurs. Source exceptionnelle de nutriments essentiels.",
            20 => "Côte à l'os de bœuf, pièce généreuse et savoureuse. Parfaite grillée ou rôtie, l'os apporte une saveur incomparable. Idéale pour les amateurs de viande authentique et goûteuse.",
            
            // VOLAILLE - Poulet fermier
            21 => "Cuisses de poulet fermier, élevé au grain. Chair savoureuse et moelleuse, parfaite rôtie ou confite. Peau croustillante garantie. Idéal pour vos plats familiaux et mijotés.",
            22 => "Filets de poulet fermier Label Rouge. Chair blanche tendre et juteuse, sans antibiotiques. Parfaits grillés, poêlés ou en escalopes. La qualité fermier fait toute la différence.",
            23 => "Poulet fermier entier, élevage traditionnel au grain. Chair ferme et savoureuse, peau dorée. Parfait rôti au four avec herbes de Provence. Idéal pour 4-6 personnes.",
            24 => "Poulet PAC jaune, qualité standard. Chair tendre et économique, parfait pour la cuisine quotidienne. Idéal pour les currys, ragoûts et plats mijotés. Excellent rapport qualité-prix.",
            
            // VOLAILLE - Morceaux
            25 => "Cuisses de poulet, morceaux savoureux et économiques. Chair moelleuse, parfaites au four ou en sauce. Cuisson lente recommandée pour une tendreté optimale.",
            26 => "Ailes de poulet, parfaites pour l'apéritif. Idéales marinées et grillées au barbecue. Chair tendre et peau croustillante. Incontournables pour vos soirées conviviales.",
            27 => "Pilons de poulet, les préférés des enfants. Chair juteuse et tendre, faciles à manger. Parfaits au four avec épices ou en sauce barbecue. Portion idéale pour les petits.",
            28 => "Filets de poulet, chair blanche et tendre. Polyvalents et rapides à cuire. Parfaits poêlés, grillés ou en escalopes. Base idéale pour tous vos plats de volaille.",
            
            // VOLAILLE - Dinde
            29 => "Filets de dinde, chair maigre et riche en protéines. Alternative saine au porc et bœuf. Parfaits rôtis ou en escalopes. Texture tendre et saveur délicate.",
            30 => "Cuisses de dinde, morceaux généreux et savoureux. Parfaites confites ou rôties lentement. Chair moelleuse et parfumée. Idéales pour vos plats de fête.",
            
            // AGNEAU
            31 => "Collier d'agneau, morceau traditionnel et savoureux. Parfait pour les navarin et ragoûts. Chair tendre aux saveurs authentiques d'agneau. Cuisson lente recommandée.",
            32 => "Épaule d'agneau, pièce généreuse et savoureuse. Parfaite rôtie aux herbes de Provence. Chair persillée et parfumée. Idéale pour vos repas de printemps.",
            33 => "Gigot d'agneau, la pièce noble par excellence. Chair rosée et tendre, parfaite rôtie rose. Saveur délicate et parfumée. L'incontournable des repas de fête.",
            34 => "Côtelettes d'agneau, morceaux tendres et savoureux. Parfaites grillées ou poêlées. Cuisson rapide et rosée recommandée. Élégantes pour vos dîners raffinés.",
            35 => "Côte filet d'agneau, pièce premium. Chair très tendre et saveur délicate. Parfaite grillée rose. Le summum de la finesse pour vos occasions spéciales.",
            36 => "Foie et cœur d'agneau, abats savoureux et nutritifs. Parfaits poêlés aux oignons. Saveurs typées appréciées des connaisseurs. Riches en fer et vitamines.",
            
            // VEAU
            37 => "Épaule de veau avec os, morceau tendre et délicat. Parfaite en rôti ou en blanquette. Chair rosée aux saveurs subtiles. L'os apporte moelleux et saveur.",
            38 => "Collier de veau, morceau économique et savoureux. Idéal pour les blanquettes et ragoûts. Chair tendre qui se détache facilement. Parfait pour la cuisine familiale.",
            39 => "Bas de carré de veau avec os, morceau tendre et parfumé. Excellent rôti ou en côtelettes. Chair rosée et délicate. Les os apportent saveur et moelleux.",
            40 => "Jarret de veau, le roi des osso-buco. Riche en collagène, parfait pour les plats mijotés. Chair fondante et moelle savoureuse. Un classique de la cuisine italienne.",
            41 => "Morceaux à blanquette de veau. Sélection de morceaux tendres : épaule, collier, tendron. Parfaits pour la blanquette traditionnelle. Chair délicate et sauce onctueuse garanties.",
            42 => "Escalopes de veau, fines tranches dans la noix. Chair très tendre et délicate. Parfaites poêlées au beurre ou panées. Cuisson rapide pour préserver la tendreté.",
            43 => "Côtes de veau, morceaux nobles et tendres. Parfaites grillées ou poêlées. Chair rosée et saveur délicate. Idéales pour vos repas raffinés.",
            44 => "Rôti de veau dans la noix, pièce tendre et délicate. Parfait rôti rose au four. Chair fine et saveur subtile. Le choix des connaisseurs pour les grandes occasions.",
            45 => "Filet de veau, la pièce la plus tendre. Chair exceptionnellement fine et délicate. Parfait poêlé ou rôti. Le summum de la tendreté et de la finesse.",
            46 => "Foie de veau, abat noble et délicat. Saveur fine et texture fondante. Parfait poêlé aux herbes ou en terrine. Riche en vitamines et fer.",
            47 => "Pieds de veau, base traditionnelle des gelées. Riches en collagène, parfaits pour les sauces onctueuses. Ingrédient secret des grands cuisiniers. Vendus par pièce.",
            
            // CHARCUTERIE FRAÎCHE
            48 => "Merguez épicées, saucisses traditionnelles orientales. Mélange bœuf-agneau aux épices authentiques. Parfaites grillées au barbecue. Saveurs relevées pour vos grillades.",
            49 => "Chipolatas aux herbes de Provence. Saucisses de porc parfumées aux herbes fraîches. Idéales grillées ou poêlées. Saveur méditerranéenne authentique.",
            50 => "Saucisses au curry, création originale de la maison. Porc épicé aux saveurs exotiques. Parfaites pour vos barbecues originaux. Dépaysement garanti.",
            51 => "Saucisses au fromage, spécialité fondante. Porc et fromage fondu à cœur. Idéales poêlées lentement. Les enfants adorent cette création gourmande.",
            52 => "Saucisses orientales, mélange d'épices authentiques. Saveurs de cumin, coriandre et paprika. Parfaites grillées pour vos soirées à thème. Voyage culinaire assuré.",
            53 => "Chorizette, petit chorizo français. Saucisse de porc au paprika doux. Parfaite à l'apéritif ou en tapas. Saveur douce et parfumée, facile à partager.",
            54 => "Saucisses de volaille, alternative légère. Chair de porc et volaille mélangées. Moins grasses que les saucisses traditionnelles. Parfaites pour une cuisine plus saine.",
            
            // PRODUITS MARINÉS ET PRÉPARÉS
            55 => "Hauts de cuisses de poulet désossés marinés. Marinés 24h aux herbes et épices. Chair moelleuse et parfumée. Prêts à cuire, idéals pour les pressés.",
            56 => "Pilons de poulet marinés, saveurs méditerranéennes. Marinés aux herbes de Provence et citron. Tendres et parfumés. Parfaits au four ou au barbecue.",
            57 => "Filets de poulet marinés, prêts à cuire. Marinés aux fines herbes et ail. Chair tendre et parfumée. Cuisson rapide pour un résultat savoureux.",
            58 => "Brochettes de poulet au curry. Morceaux de filet mariné aux épices indiennes. Saveurs exotiques et parfumées. Parfaites au barbecue ou à la plancha.",
            59 => "Brochettes de poulet orientales. Marinade aux épices du Maghreb. Cumin, coriandre et harissa. Voyage culinaire assuré, saveurs authentiques.",
            60 => "Brochettes de poulet provençales. Marinade aux herbes de Provence et tomates confites. Saveurs du Sud garanties. Parfaites pour l'été au barbecue.",
            61 => "Brochettes de bœuf, morceaux nobles marinés. Sélection dans le faux-filet et rumsteak. Marinés aux herbes et vin rouge. Tendreté et saveur garanties.",
            62 => "Brochettes de veau, délicatesse marinée. Morceaux dans la noix, marinés aux herbes fines. Chair tendre et saveur délicate. Raffinement à la plancha.",
            63 => "Kefta assaisonnée, spécialité orientale. Viande hachée d'agneau aux épices traditionnelles. Façonnées à la main selon la tradition. Authentiques saveurs du Maghreb.",
            64 => "Chair à farcir de veau, base de vos préparations. Viande hachée finement, parfaite pour farces et terrines. Saveur délicate du veau. Laissez libre cours à votre créativité.",
            
            // PRODUITS ÉLABORÉS
            65 => "Cordons bleus maison, élaborés dans nos ateliers. Escalope de porc, jambon et fromage fondu. Panure croustillante. Le plaisir des petits et grands.",
            66 => "Nuggets de poulet maison, sans conservateurs. Morceaux de filet panés traditionnellement. Croustillants à l'extérieur, moelleux à l'intérieur. Alternative saine aux industriels.",
            67 => "Aiguillettes de poulet panées. Lamelles de filet dans une panure dorée. Cuisson rapide et croustillant garanti. Parfaites pour les enfants et les pressés.",
            
            // GIBIER
            68 => "Lapin fermier entier, élevage traditionnel. Chair blanche et délicate, saveur authentique. Parfait rôti aux herbes ou en civet. Viande maigre et savoureuse, idéale pour une cuisine saine."
        ];

        $io->title('Mise à jour des descriptions produits');
        $io->text('Récupération de tous les produits...');

        $products = $this->productRepository->findAll();
        $updated = 0;

        foreach ($products as $product) {
            if (isset($descriptions[$product->getId()])) {
                $product->setDescription($descriptions[$product->getId()]);
                $updated++;
                $io->text("✅ Produit {$product->getId()}: {$product->getName()}");
            }
        }

        $this->entityManager->flush();

        $io->success("✅ {$updated} descriptions mises à jour avec succès !");
        $io->note('Les descriptions sont maintenant appétissantes et professionnelles.');

        return Command::SUCCESS;
    }
}
