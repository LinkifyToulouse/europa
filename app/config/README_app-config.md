## FICHIER config.json
Config contient toutes les informations de configuration d'Europa.
La version du framework, la date de publication et l'environnement (`dev` ou `prod`) sont spécifiés.
Au moins un domaine doit être référencé. Il permet de gérer un sous-domaine ou une section du site. Dans chacun des objets `domain`, il convient d'indiquer le domaine (avec sous-domaine le cas échéant), le protocole et le chemin racine.
L'objet `redirectRules` contient toutes les exceptions de redirection qui s'appliqueront à tous les domaines. Il est possible d'y indiquer, sous forme d'expression régulière, les requêtes à ne pas rediriger vers le Kernel.
Config contient également les informations de connexion à une base de données. Chacune de ces informations doit valoir false pour que la base de données ne soit pas connectée. Si une information est manquante, une exception est levée.  Si autoload vaut true, la base de données sera connectée automatiquement.
Enfin, config regroupe les espaces de noms personnalisés pour lesquels l'utilisateur veut rendre l'auto-chargement des classes possible.

Il convient de mettre à jour Config avant d'initialiser le framework (cf. Kernel). 

## FICHIER routes.json
Routes rassemble l'ensemble des routes du framework.
Chaque route est contenue au sein d'un objet reprenant le nom du domaine pour lequel elles s'appliquent.
Pour chaque route, il est nécessaire de préciser:
* Le nom de la route (name),
* Le contrôleur à appeler pour charger le contenu (controller),
* La méthode à appeler au sein du contrôleur, (function)
* Le schéma de la route (`scheme`). L'intégration des variables est possible (la variable s'écrit sous la forme `lorem${variable}ipsum`).