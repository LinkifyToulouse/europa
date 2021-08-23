<?php

namespace Europa\Core;

class RouteHandler {
	
	public static $currentDomainRoutes = array();
	public static $URLattributes = array();
	public static $currentRoute = null;
	public static $routes = null;
	public static $alias = null;
	
	public static function loadRoutes() {
		$tmp_routes = file_get_contents(EUROPA_DEFAULT_PATH.'app/config/routes.json');
		$tmp_routes = json_decode($tmp_routes, TRUE);
		self::$routes = $tmp_routes['routes'];
		self::$alias = $tmp_routes['alias'];
		$routes = $tmp_routes['routes'];
		
		if (isset($routes[RequestHandler::$currentDomain])) {
			if (!count($routes[RequestHandler::$currentDomain])) {
				throw new \Europa\Core\Exceptions\CoreException("Aucune route n'est correctement définie pour le domaine courant dans routes.json");
			}
			self::$currentDomainRoutes = $routes[RequestHandler::$currentDomain];
		} else {
			throw new \Europa\Core\Exceptions\CoreException("Aucune route n'est correctement définie pour le domaine courant dans routes.json");
		}
		self::handleRoutes();
	}
	
	private static function handleRoutes() {
		if (isset(self::$alias[RequestHandler::$currentDomain])) {
			
			foreach (self::$alias[RequestHandler::$currentDomain] as $an=>$av) {
				if (preg_match("#^$an\$#", RequestHandler::$request)) {
					ResponseHandler::redirect(ResponseHandler::parsePath($av));
				}
			}
		}
		foreach(self::$currentDomainRoutes as $k=>$route) {
					 
			if (!isset(self::$currentRoute)) {
				preg_match_all('#\${(?<attr>.+)}#U', $route['scheme'], $attrNames);
				$attrNames = $attrNames['attr'];

				$routeToRegex = preg_replace('#\${(.+)}#U',"(.+)",$route['scheme']);

				if (preg_match("#^$routeToRegex\$#", RequestHandler::$request)) {
					
					self::$currentRoute = $route;
					if (count($attrNames)) {
						preg_match_all("#^$routeToRegex\$#", RequestHandler::$request, $attrValues);
						unset($attrValues[0]);
						if (count($attrValues) !== count($attrNames)) {
							throw new \Europa\Core\Exceptions\CoreException("Une erreur est survenue lors du parsing des variables de la requête.");

						}
						$URLattributes = array();
						foreach ($attrNames as $k=>$atn) {
							$URLattributes[$atn] = $attrValues[intval($k)+1][0];
						}
						self::$URLattributes = $URLattributes;
					}
					break;
				}
			}
		}
		
		if (!isset(self::$currentRoute)) {
			$controller = new \Europa\Controller\DefaultControllers\ExceptionController();
			$controller->status404();
			return false;
		} else {
			self::loadController();
			return true;
		}
		
		//var_dump(self::$currentRoute);
	}
	
	private static function loadController() {
		eval('$controller = new \Europa\Controller\\'.self::$currentRoute['controller'].';
		$controller->'.self::$currentRoute['function'].'();');
		
	}
	
	public static function getURLAttribute($attrName) {
		return self::$URLattributes[$attrName];
	}
	
}
