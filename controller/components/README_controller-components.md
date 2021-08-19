## FICHIER ComponentsManager.php
ComponentsManager permet le traitement des components dans les vues.


# Utilisation des components
Un component est une balise HTML personnalisée, qui, une fois traitée par Europa, est remplacée par un contenu prédéfini.

Dans le markup, le component se définit comme suit :
`
<c:name attribute="value">
	Contenu
</c:name>
`
Chaque balise component doit être préfixée par `c:`. Elle peut comporter des attributs HTML (uniquement entre double guillemets). Elle peut être de type inline ou block.

Il n'est pas nécessaire de déclarer l'ensemble des components utilisés. En revanche, une fonction de callback doit exister pour chaque component.

- Si vous ne faites pas usage des components, il faut initialiser le Kernel dans public/europa.php en passant `"AUTOLOAD_COMPONENTS"=>false` dans les paramètres de la méthode __construct. Par défaut, Europa tentera de parser les components. S'il n'y en a pas, aucune exception ne sera levée.


# Définition des fonctions de parsing
Les fonctions de callback doivent être crées dans des fichiers portant le nom du component. Ces fichiers doivent se trouver dans le dossier `controller/components/components`. Le nom de la fonction doit être préfixé par `component_` et déclarée dans l'espace de nom Europa\Vue\Components .

Attention : les fonctions de parsing doivent être des fonctions et non pas des classes. Il ne doit y avoit qu'une fonction par fichier (un fichier -> une fonction -> un component).

Ainsi, pour le component `test`, la balise HTML prendra la forme `<c:test></c:test>` et la fonction correspondante sera créée dans le fichier `test.php` sous le nom `component_test`.

Chaque fonction prend pour paramètre un tableau associatif. Ce tableau inclut plusieurs éléments :
* `block` (string) contient l'ensemble de la balise telle que présente dans le markup.
* `content` (string) contient le contenu de la balise si elle est de type block.
* `name` (string) est le nom du component
* `attributes` (array) est un tableau contenant les attributes (attribut => valeur)

La fonction n'a pas besoin d'un `return`. Elle peut renvoyer du contenu HTML par la fonction `echo` ou en fermant la balise PHP (`?> markup... <?php`).