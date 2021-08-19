<?php

namespace Europa\Core;

class RequestHandler {
	
	public static $request = null;
	public static $currentDomain = null;
	public static $currentDomainParameters = null;
	public static $defaultPath = null;
	
	public static function init($autoHandlerRoutes = false) {
		foreach (Kernel::$DOMAINS as $dn=>$domain) {
			if ($domain['domain'] === $_SERVER['SERVER_NAME']) {
				self::$currentDomain = $dn;
				self::$currentDomainParameters = $domain;
			}
		}
		if (!isset(self::$currentDomain)) {
			throw new \Europa\Core\Exceptions\CoreException("La requête ne relève d'aucun domaine configuré dans config.json");
		}
		if (!isset($_GET['_req'])) {
			throw new \Europa\Core\Exceptions\CoreException("La requête n'a pas été transmise au Kernel (\$_GET manquant)");
		}
		self::$request = $_GET['_req'];
		
		if (isset(self::$currentDomainParameters["subdomain"])) {
			self::$currentDomainParameters["subdomain"] .= '.';
		} else {
			self::$currentDomainParameters["subdomain"] = '';
		}
		
		self::$defaultPath = self::$currentDomainParameters["protocol"]."://".self::$currentDomainParameters["subdomain"].self::$currentDomainParameters["domain"]."/".self::$currentDomainParameters["path"];
		
		if ($autoHandlerRoutes) {
			RouteHandler::loadRoutes();
		}
	}
	
}
