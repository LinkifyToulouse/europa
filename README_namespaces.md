# Espaces de nom et classes principales dans Europa
```
\Europa													(Namespace global du framework)
|
|___\Core												(Namespace du cœur du framework)
|	|___\Kernel (classe)								(Kernel du framework)
|	|___\RequestHandler (classe)
|	|___\RouteHandler (classe)
|	|___\ResponseHandler (classe)
|	|
|	|___\Exceptions
|	|	|___\ExceptionsDefinition (classe)
|	|
|	|___\Autoload
|	|	|___\Autoloader (classe)
|	|
|	|___\Constants
|		|___\ConstantsDefinition (classe)				(NB : Les constantes sont définies dans le namespace global)
|
|___\Controller											(Namespace des contrôleurs de route (dans controller/routeController))
|
|___\Dependency											(Namespace des dependencues (dans controller/dependencies))
|
|___\Vue												(Namespace du VueManager dans vues/vueManager)
 	|___\Components										(Namespace des components dans controller/components/components)
	|
	|___\ComponentsManager								(Namespace du ComponentManager dans controller/components/ComponentsManager)
```
