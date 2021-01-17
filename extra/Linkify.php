<?php
namespace Extra {
	class Linkify {
		public static function path(string $path):string {
			return \App\Kernel\Kernel::$defaultPath.$path;
		}
		public static function route(string $route):string {
			$r = null;
			foreach(\App\Kernel\RouteHandler::$routes['routes'] as $k=>$v) {
				if ($v['name'] == $route) {
					return \App\Kernel\Kernel::$defaultPath.$v['face'];
					$r = true;
				}
			}
			if ($r === null) {
				return false;
			}
		}
	}
}
