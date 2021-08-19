# Linkify Europa 2.0.0
Linkify Europa est un framework MVC basé sur PHP et conçu pour les serveurs Apache. Inspiré de Symfony, il est proche du PHP natif et ne requiert aucune installation logicielle supplémentaire.
Linkify Europa est prévu pour maintenir le code, optimiser les tâches répétitives sans avoir à installer aucun framework complexe et en conservant l'utilisation du PHP dans les vues. Linkify Europa est basé sur le modèle MVC. Chacune des composantes du framework est distincte.
Linkify Europa est conçu pour supporter MySQL et permet la création de components, des entités HTML personnalisées

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
|__app
|__controller
|__public
|__vues
```
Ces sous-dossiers ne __doivent pas être supprimés__.
