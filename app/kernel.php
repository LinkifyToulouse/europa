<?php

/* 	-- EUROPA kernel --	*/

// App\Kernel : classes du kernel
namespace App\Kernel {

	//Initiation d'Europa
	class EuropaInitiator {

		//Array contenant les paramètres de config.json
		public static $config = null;
		
		//Array contenant les paramètres de env.json
		public static $env = null;
		
		//Array contenant les paramètres de database.json
		public static $database = null;
		
		//Array contenant les paramètres de routes.json
		public static $routes = null;

		//Chargement des paramètres de configuration et sauvegarde en attributs statiques de la classe App\Kernel\EuropaInitiator
		public static function loadConfig():bool {
			$configJSON = \file_get_contents('../config/config.json');
			self::$config = \json_decode($configJSON, true);

			$envJSON = \file_get_contents('../config/env.json');
			self::$env = \json_decode($envJSON, true);

			$databaseJSON = \file_get_contents('../config/database.json');
			self::$database = \json_decode($databaseJSON, true);

			$routesJSON = \file_get_contents('../config/routes.json');
			self::$routes = \json_decode($routesJSON, true);
			
			if (!$configJSON || !$envJSON || !$databaseJSON || !$routesJSON) {
				return false;
			} else {
				return true;
			}
		}
		
		//Chargement des requirements définis dans config.json
		public static function loadRequirements() {
			foreach(\App\Kernel\EuropaInitiator::$config['config']['requirements'] as $k=>$v) {
				require('../extra/'.$v['name'].'.php');
			}
			foreach(\App\Kernel\EuropaInitiator::$config['config']['dependencies'] as $k=>$v) {
				require('../controller/dependencies/'.$v['name'].'.php');
			}
			if (isset(\App\Kernel\EuropaInitiator::$database['database']['dbname'])) {
				\Extra\AxSQL::init();
			}
			Kernel::$defaultPath = \App\Kernel\EuropaInitiator::$config['config']['defaultPath'];
			return true;
		}

	}
	
	//Gestion des requêtes
	class Request {
		
		//Requête brute (/(.+))
		public static $requestRaw = null;
		
		//Enregistrement de la requête brute en attribut statique de la classe App\Kernel\Request
		public static function load():bool {
			self::$requestRaw = $_GET['_req'];
			return true;
		}
		
	}
	
	//Gestion et envoi de la réponse
	class Response {
		//Chargement d'un template
		public static function send($url) {
			include('../template/'.$url);
		}
		
		//Inclusion d'un élément de template
		public static function include($url) {
			include('../template/'.$url.'.php');
		}
		
		//Insertion d'un header
		public static function setHeader($header) {
			\header($header);
		}
		
		public static function display (string $text) {
			echo $text;
		}
		
		//Redirection
		public static function redirect($url) {
			\header('Location: '.Kernel::$defaultPath.$url);
		}
	}
	
	//Gestion des routes
	class RouteHandler {
		
		//Array des routes enregistrées
		public static $routes = null;
		
		//Controller utilisé pour la route
		public static $CONTROLLER;
		
		//État du traitement de la requête
		public static $handled = false;
		
		//Chargement de la configuration des routes en attribut statique de la classe App\Kernel\EuropaInitiator vers un attribut statique de la classe App\Kernel\RouteHandler
		public function __construct() {
			self::$routes = EuropaInitiator::$routes;
			return true;
		}
		
		//Retourne une valeur du tableau TemplateHydrate
		public static function getTemplateValue(string $key) {
			return self::$CONTROLLER::$TEMPLATE_PARAMETERS[$key];
		}
		
		//Vérification de l'enregistrement des routes en attribut statique de la classe App\Kernel\RouteHandler
		public static function checkRoutesConfig(bool $load = false):bool {
			if ($load) {
				Request::load();
				EuropaInitiator::loadConfig();
				self::$routes = EuropaInitiator::$routes;
			}
			if (self::$routes === null) {
				return false;
			} else {
				return true;
			}
		}
		
		//Prise en charge de la requête par rapport aux routes enregistrées
		public function handleRequest(bool $load = false) {
			if (self::checkRoutesConfig($load)) {
				foreach (self::$routes['alias'] as $k => $v) {
					$regexAlias = '#^'.$k.'$#';
					if (preg_match($regexAlias, Request::$requestRaw)) {
						self::$handled = true;
						$H = true;
						http_response_code(301);
						Response::redirect($v);
					}
				}
				foreach (self::$routes['routes'] as $k => $v) {
					$regexRoute = '#^'.$v['face'].'$#';
					if (preg_match($regexRoute, Request::$requestRaw)) {
						self::$handled = true;
						$H = true;
						$controller = preg_replace('#(.*):(.*)#', '$1', $v['controller']);
						$class = preg_replace('#(.*):(.*)#', '$2', $v['controller']);
						$currentConfig = $v;
						$currentConfig['req'] = Request::$requestRaw;
						require '../controller/'.$controller.'.php';
						http_response_code(200);
						eval('self::$CONTROLLER = new Controller'.trim(" \ ").$controller.'($v); self::$CONTROLLER::$TEMPLATE_PARAMETERS["canonicalURL"] = "'.Kernel::$defaultPath.Request::$requestRaw.'"; self::$handled = self::$CONTROLLER->'.$class.'; ');
					}
				}
			}
			if (self::$handled == false && !isset($H)) {
				require '../controller/ExceptionController.php';
				self::$CONTROLLER = new \Controller\ExceptionController([]);
				self::$CONTROLLER::$TEMPLATE_PARAMETERS["canonicalURL"] = Kernel::$defaultPath;
				self::$CONTROLLER->send404error();
			}
			return self::$handled;
		}
	}
	
	//Kernel du framework
	class Kernel {
		
		//Chemin du dossier EUROPA
		public static $defaultPath;
		
		//Interaction DB
		public static $dbInteract;
		
		//Exécution du framework en fonction du mode de développement
		public function __construct() {
			Request::load();
			EuropaInitiator::loadConfig();
			if (EuropaInitiator::loadRequirements()) {
				if (isset(EuropaInitiator::$database['db'])) {
					self::$dbInteract = new \PDO('mysql:host='.EuropaInitiator::$database['db']['host'].';dbname='.EuropaInitiator::$database['db']['database'].';charset=utf8', EuropaInitiator::$database['db']['username'], EuropaInitiator::$database['db']['password']);
				}
				
				if (isset(EuropaInitiator::$env['env'])) {
					if (EuropaInitiator::$env['env'] == 'prod') {
						register_shutdown_function('\App\Kernel\fatalErrorHandler');
						error_reporting(0);
						ini_set('display_errors', 0);
						$requestHandler = new RouteHandler;
						$requestHandler->handleRequest();
					} else {
						error_reporting(E_ALL);
						ini_set('display_errors', 1);
						$requestHandler = new RouteHandler;
						$requestHandler->handleRequest();
					}
				}
			}
		}
		
	}
	
	
}

?>
