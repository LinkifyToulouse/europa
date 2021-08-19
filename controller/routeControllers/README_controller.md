# Contrôleurs
Les contrôleurs sont des classes appelées automatiquement par Europa pour traiter une route.
Chaque contrôleur doit être défini dans le namespace Europa\Controller et dans un fichier portant le nom de la classe, dans controller/routeControllers.

Dans le dossier default, un ExceptionController permet de gérer les erreurs, notamment le statut HTTP 404. Il peut être personnalisé pour gérer d'autres statuts et afficher une vue personnalisée.


# Dépendances
Les dépendances sont des scripts PHP complémentaires définis dans controller/dependencies.
Chaque dépendance se présente sous forme de classe. Le nom de la classe doit être identique au nom du fichier. La classe doit être définie dans le namespace \Europa\Dependency.