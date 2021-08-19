# Fonctionnement des vues
Les vues sont les modèles à afficher dans le HTML rendu.
Chaque vue se présente sous la forme d'un fichier (php, html...) dans le dossier vues/templates/.
Les vues sont gérées par VueManager.

Depuis la vue, les méthodes de VueManager peuvent être appelées dans le contexte objet.
L'inclusion de vues est possible via la méthode include.
La propriété $attributes (array) permet de récupérer les attributs transmis depuis le contrôleur.
La propriété $URLAttributes (array) permet de récupérer les attributs de l'URL parsée.