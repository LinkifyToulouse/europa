<?php

namespace Europa\Core;

class ResponseHandler {
	
	public static function setHeader($header) {
		if (gettype($header) == 'array') {
			foreach ($header as $h) {
				\header($h);
			}
		} else {
			\header($header);
		}
	}
	
	public static function render($vueFileName, $vueFileParameters = null) {
		return new \Europa\Vue\VueManager($vueFileName, $vueFileParameters);
	}
	
	public static function parsePath($path) {
		return RequestHandler::$defaultPath.$path;
	}
	
	public static function parseRoute($routeName, $routeParameters = array()) {
		foreach (RouteHandler::$routes[RequestHandler::$currentDomain] as $route) {
			if ($route['name'] == $routeName) {
				if (!count($routeParameters)) {
					return RequestHandler::$defaultPath.$route['scheme'];
				} else {
					$rtn = $route['scheme'];
					foreach ($routeParameters as $n=>$p) {
						$rtn = preg_replace('#\${'.$n.'}#',$p,$rtn);
					}
					return RequestHandler::$defaultPath.$rtn;
				}
			}
		}
		return false;
	}
	
	public static function redirect($url) {
		\header("Location: $url");
		exit();
	}
	
}
