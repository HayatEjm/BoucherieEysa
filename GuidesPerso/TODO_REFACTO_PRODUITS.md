# TODO refactorisation produits (juillet 2025)

- [ ] Créer un partial Twig unique pour l’affichage des produits (ex : `product/_product_grid.html.twig`)
- [ ] Utiliser ce partial dans toutes les pages listant des produits (page "Nos produits", page catégorie, etc.)
- [ ] S’assurer que la logique JS/CSS d’interaction produit (quantité, ajout panier, etc.) est bien externalisée et générique
- [ ] Supprimer toute duplication d’affichage produit dans les templates
- [ ] Garder un affichage simple, cohérent et maintenable sur toutes les pages produits
- [ ] (Optionnel) Ajouter des options au partial pour personnaliser l’affichage selon le contexte (ex : titre, filtre, etc.)

**But :**
- Un seul affichage produit pour tout le site
- Maintenance et évolutions facilitées
- Respect du MVC et du DRY
