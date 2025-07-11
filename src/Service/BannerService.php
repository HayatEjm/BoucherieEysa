<?php

namespace App\Service;

class BannerService
{
    /**
     * Retourne les données du bandeau selon la route/page
     */
    public function getBannerData(string $routeName): array
    {
        $banners = [
            // Page d'accueil
            'homepage' => [
                'title' => 'Découvrez notre sélection de produits',
                'subtitle' => 'Chaque jour, nous vous proposons des produits de qualité supérieure, sélectionnés avec soin par nos maîtres bouchers.',
                'image' => 'images/banner-home.jpg',
                'button_text' => 'COMMANDER',
                'button_link' => '/click-collect',
                'background_color' => '#8B4513'
            ],
            
            // Page Click & Collect
            'click_collect' => [
                'title' => 'Click & Collect',
                'subtitle' => 'Commandez vos viandes fraîches en ligne et venez les retirer directement en magasin. Simple, rapide et pratique !',
                'image' => 'images/banner-click-collect.jpg',
                'button_text' => 'COMMENCER',
                'button_link' => '/categories',
                'background_color' => '#A0522D',
                'show_badges' => true // Active les badges pour cette page
            ],
            
            // Page Bœuf
            'category_beef' => [
                'title' => 'Le bœuf',
                'subtitle' => 'Découvrez notre sélection de viandes de bœuf d\'exception',
                'image' => 'images/boeuf.jpg',
                'button_text' => 'VOIR LES PRODUITS',
                'button_link' => '/category/boeuf',
                'background_color' => '#8B0000'
            ],
            
            // Page Panier
            'cart' => [
                'title' => 'Votre Panier',
                'subtitle' => 'Vérifiez vos produits sélectionnés avant validation',
                'image' => 'images/banner-cart.jpg',
                'button_text' => 'FINALISER',
                'button_link' => '/checkout',
                'background_color' => '#654321'
            ],
            
            // Page Ma commande
            'order' => [
                'title' => 'Ma commande',
                'subtitle' => 'Suivez l\'état de votre commande en temps réel',
                'image' => 'images/banner-order.jpg',
                'button_text' => 'VOIR DÉTAILS',
                'button_link' => '/order/details',
                'background_color' => '#B8860B'
            ]
        ];
        
        // Retourne le bandeau par défaut si la route n'existe pas
        return $banners[$routeName] ?? $banners['homepage'];
    }
}
