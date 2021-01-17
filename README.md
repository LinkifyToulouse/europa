# Linkify Europa
Linkify Europa est un framework MVC basé sur PHP et conçu pour les serveurs Apache. Inspiré de Symfony, il est proche du PHP natif et ne requiert aucune installation logicielle supplémentaire.
Linkify Europa est prévu pour maintenir le code, optimiser les tâches répétitives sans avoir à installer aucun framework complexe et en conservant l'utilisation du PHP dans les vues. Linkify Europa est basé sur le modèle MVC. Chacune des composantes du framework est distincte.

## Installation
#### Installation via NPM
Linkify Europa peut être installé via npm.
```
$ npm install @linkify/europa
```
Une fois le package installé dans votre dossier racine, saisir la commande suivante :
```
$ europa-create-project my-europa-project
```
Les fichiers du nouveau projet Europa seront créés dans le dossier `my-europa-project`.

#### Installation sans NPM
Il est possible d'installer Linkify Europa sans NPM. Pour cela, télécharger __tous__ les dossiers et fichiers de ce git dans votre dossier de projet.

## Structure du framework
Par défaut, Linkify Europa est constitué de plusieurs sous-dossiers :
```
my-europa-project
|
|__config
|__app
|__controller
|__public
|__extra
|__template
```
Ces sous-dossiers ne __doivent pas être supprimés__.

#### config
Le dossier `config` contient les fichiers de configuration du projet.
Il s'agit de fichiers au format JSON.

#### app
Le dossier `app` contient le script PHP du Kernel du framework. Le Kernel gère le traitement des requêtes et réponses envoyés au client.

#### controller
`controller` contient tous les contrôleurs et les dépendances utilisées pour le projet.

#### public
Chaque requête doit être envoyée au dossier `public` : le nom de domaine principal de votre site doit pointer vers ce dossier. La page `index.php` et le fichier `.htaccess` ne __doivent pas être supprimés__. Il est possible de créer des sous-dossiers pour les assets et contenus. Les noms autorisés pour ces sous-dossiers sont `assets`, `files`, `content` et `europa`.

#### extra
Le dossier `extra` contient toutes les fonctions additionnelles du framework.

#### template
`template` contient les templates des vues.
