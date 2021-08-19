- Aucune modification ne doit être apportée aux fichiers de ce dossier. -

## FICHIER Kernel.php
Le Kernel est le noyau du framework.
Il centralise l'ensemble des paramètres, des variables et coordonne l'appel aux différentes fonctions.
Il assure le traitement des requêtes et l'affichage des réponses.

Le Kernel dispose de plusieurs propriétés :
* $ENV, $DOMAINS, $CUSTOM_NAMESPACES, $REDIRECT_RULES et $DATABASE sont des attributs statiques qui contiennent les informations issues de app/config/config.json.
* $OPTIONS est une array qui contient les informations envoyées au Kernel lors de sa génération. Par défaut, $OPTIONS est une array qui contient :
	* 'LOADING_TYPE' => 'KERNEL_DEFAULT_LOADING' (Le Kernel ne mettre pas à jour le .htaccess contenant les règles de redirection. Les derniers paramètres enregistrées tendront donc à s'appliquer. Toutefois, si la valeur de 'LOADING_TYPE' est 'KERNEL_INIT_EUROPA', le .htaccess sera mis à jour. Il convient de ne sélectionner cette option uniquement pour une première initialisation après modification du config.json.)
	* 'AUTOLOAD_COMPONENTS' => true (Les components seront parsés automatiquement. Définir à false si aucun component n'est présent dans les vues.)

Plusieurs méthodes sont définies et appelées consécutivement par le Kernel. La seule méthode publique est __construct. Les autres méthodes permettent le chargement des configurations, l'initialisation de la base de données si définie et la mise à jour de .htaccess. Kernel appelera ensuite RequestHandler.
En cas d'erreur, une CoreException est levée.


## FICHIER RequestHandler.php
RequestHandler est une classe qui traite les requêtes.

Elle contient plusieurs propriétés statiques :
* $request contient la valeur de la requête (contenue dans l'URL, après le nom de domaine ou le chemin par défaut)
* $currentDomain est le nom du domaine courant (défini dans config.json). Le domaine courant est déterminé par l'URL, en fonction du nom de domaine et du sous-domaine le cas échéant.
* $currentDomainParameters est un array contenant les paramètres du domaine courant (défini dans config.json)
* $defaultPath est le chemin par défaut pour le domaine courant (composé de `protocol`://`subdomain`.`domain`/`path`)

Une seule méthode statique, init(), permet de mettre à jour les propriétés et d'appeler RouteHandler.
En cas d'erreur, une CoreException est levée.


## FICHIER RouteHandler.php
RouteHandler est une classe appelée par RequestHandler pour traiter les routes.

RouteHandler contient plusieurs propriétés statiques :
* $routes est un tableau qui contient l'ensemble des routes classées par domaine (issu de routes.json).
* $currentDomainRoutes est un tableau qui regroupe les routes pour le domaine courant
* $currentRoute est un tableau qui contient le nom, le contrôleur, la méthode du contrôleur et le schéma de la route courante.
* $URLattributes est un tableau qui contient les attributs de l'URL en fonction du schéma donné. RouteHandler parse automatiquement les attributs si définis dans le schéma.

Plusieurs méthodes statiques sont contenues dans RouteHandler :
* loadRoutes(), qui charge l'ensemble des routes depuis routes.json
* handleRoutes() (privée), qui détermine la route courante et parse les attributs. Si aucune route n'est trouvée pour la requête courante, la méthode appelle \Europa\Controller\DefaultControllers\ExceptionController() et lève un statut HTTP 404. Sinon, handleRoutes() appelle loadController().
* loadController() (private) appelle le contrôleur de la route courante.
* getURLAttribute($attribute) permet de récupérer un attribut de l'URL en fonction du nom donné à l'attribut dans le schéma de la route.

En cas d'erreur, une CoreException est levée.


## FICHIER ResponseHandler.php
ResponseHandler traite l'affichage des réponses.

ResponseHeader contient plusieurs méthodes statiques publiques :
* setHeader() : définit le header passé en paramètre.
* render() : instancie un \Europa\Vue\VueManager() et permet l'affichage d'une vue. Le nom de la vue (nom du fichier sans l'extension) et un array contenant le cas échéant l'extension (si différent de .php) doivent être passés en paramètre.
* parsePath() retourne une URL basée sur le chemin par défaut du domaine courant.
* parseRoute() parse la route donnée en paramètre en remplaçant, le cas échéant, les attributs de la route par les valeurs passées en paramètres.
* redirect() : redirige le client vers l'URL transmise en paramètre.


## FICHIER DatabaseManager.php
DatabaseManager permet la connexion à une base de données MySQL et la gestion de requêtes.

Plusieurs propriétés statiques publiques existent :
* $INSTANCE contient l'instance de la classe \PDO utilisée pour la connexion à MySQL.
* $IS_ERROR est un booléen indiquant si une erreur empêche la connexion à MySQL.
* $IS_INIT est un booléen qui indique si DatabaseManager a déjà été initialisé.

Plusieurs méthodes publiques et statiques sont mises en place pour l'accès à la base de données :
* init() instancie un objet \PDO connecté à la base de données et le stocke dans $INSTANCE. Elle prend en paramètre $exceptionMode qui vaut par défaut true. Si ce booléen est vrai, toute erreur MySQL sera levée comme exception. Sinon, elle apparaîtra dans le tableau renvoyé par la méthode SQL().
* setError() enregistre qu'une erreur est survenur.
* checkError() vérifie si une erreur a été enregistrée dans $IS_ERROR.
* SQL() est l'utilitaire d'exécution de MySQL. La méthode prend plusieurs paramètres :
	* $command, la commande MySQL sous forme de string,
	* $arguments, qui vaut null par défaut : l'array de valeurs à binder dans la commande MySQL,
	* $autoFetch, booléen qui indique si DatabaseManager doit lui-même récupérer (fetchAll) les lignes dans la base.
	En réponse, SQL() répond un array :
	* 0 : un booléen indiquant si l'opération s'est convenablement déroulée,
	* 1 : un objet \PDOStatement,
	* 2 : un array contenant les informations d'erreur MySQL (1 => statut, 2 => code, 3 => message d'erreur),
	* 3 : si autoFetch vaut true, contient un array avec les lignes retournées par MySQL.
* fetchSQL() prend en paramètre l'array renvoyé par init() et va récupérer les résultats dans la base de données. Si $autoFetch vaut true dans la méthode SQL(), il n'est pas nécessaire d'appeler cette méthode.

DatabaseManager inclut également plusieurs méthodes statiques servant d'utilitaires :
* verify() (private) analyse la chaîne passée en paramètre et vérifie que le contenu ne contienne pas de caractères indésirables. Elle renvoie true si un caractère indésirable est décelé, false sinon.
* validateValues() prend en paramètre un nombre, une chaîne, un array ou un null et utilise la méthode verify pour signaler les caractères indésirables. Une exception est levée le cas échéant.
* sanitizeArray() prend en paramètre une array, une array modèle. La méthode vérifie que les valeurs valant true dans le modèle existent dans l'array (sinon une exception est levée), supprime les valeurs valant null dans le modèle et définit les valeurs inexistantes dans l'array grâce aux valeurs par défaut renseignées dans le modèle. Si le paramètre $strictMode vaut true, sanitizeArray() supprimera les clés de l'array qui n'existent pas dans le modèle. Si le paramètre $autoValidate vaut true, sanitizeArray() appliquera en outre la méthode verify() à chaque valeur.
* slugify() prend en paramètre une chaîne et renvoie un slug de cette même chaîne.



# DOSSIER autoload

## FICHIER Autoloader.php
Autoloader permet le chargement automatique des classes du framework.
Autoloader ne sera pas initialisé si aucune instance Kernel n'est créée.

Autoloader contient deux méthodes statiques :
* configureAutoload(), qui définit Autoloader comme classe à appeler pour charger une classe,
* loadClass(), qui charge automatiquement la classe passée en paramètre (avec son namespace le cas échéant).
En cas d'erreur, une CoreException est levée.


# DOSSIER constants

## FICHIER ConstantsDefinitions.php
ConstantsDefinitions contient l'ensemble des constantes utilisées par le framework.
L'appel de la méthode statique defineConstants() définira l'ensemble des constantes dans le namespace global.


# DOSSIER exceptions

## FICHIER Exceptions.php
Exceptions regroupe et définit l'ensemble des exceptions pouvant être lancées par Europa.