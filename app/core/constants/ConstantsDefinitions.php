<?php

namespace Europa\Core\Constants;

class ConstantsDefinition {
	
	public static function defineConstants() {
		
		define ("EUROPA_ENV_DEV", "dev");
		define ("EUROPA_ENV_PROD", "prod");
		
		define ("EUROPA_DEFAULT_PATH", "../");
		
		define ("EUROPA_NS", array(
			"AppCore" =>	 					"\Europa\Core",
			"AppCoreExceptions" => 				"\Europa\Core\Exceptions",
			"AppCoreAutoload" => 				"\Europa\Core\Autoload",
			"AppCoreConstants" => 				"\Europa\Core\Constants",
			"DefaultRouteController" => 		"\Europa\Controller\DefaultControllers",
			"RouteController" => 				"\Europa\Controller",
			"VueManager" => 					"\Europa\Vue",
			"Dependency" => 					"\Europa\Dependency",
			"Components" => 					"\Europa\Vue\Components",
			"ComponentsManager" => 				"\Europa\Vue\ComponentsManager"
		));
		
		define ("EUROPA_NS_PATH", array(
			EUROPA_NS["AppCore"] => 			str_replace("|", DIRECTORY_SEPARATOR, "app|core|"),
			EUROPA_NS["AppCoreExceptions"] =>	str_replace("|", DIRECTORY_SEPARATOR, "app|core|exceptions|"),
			EUROPA_NS["AppCoreAutoload"] => 	str_replace("|", DIRECTORY_SEPARATOR, "app|core|autoload|"),
			EUROPA_NS["AppCoreConstants"] => 	str_replace("|", DIRECTORY_SEPARATOR, "app|core|constants|"),
			EUROPA_NS["DefaultRouteController"] => 	str_replace("|", DIRECTORY_SEPARATOR, "controller|routeControllers|default|"),
			EUROPA_NS["RouteController"] => 	str_replace("|", DIRECTORY_SEPARATOR, "controller|routeControllers|"),
			EUROPA_NS["VueManager"] => 	str_replace("|", DIRECTORY_SEPARATOR, "vues|vueManager|"),
			EUROPA_NS["Dependency"] => 	str_replace("|", DIRECTORY_SEPARATOR, "controller|dependencies|"),
			EUROPA_NS["Components"] => 	str_replace("|", DIRECTORY_SEPARATOR, "controller|components|components|"),
			EUROPA_NS["ComponentsManager"] => 	str_replace("|", DIRECTORY_SEPARATOR, "controller|components|componentsManager|")
		));
		
		
	}
	
}
