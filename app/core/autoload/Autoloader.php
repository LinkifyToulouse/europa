<?php

namespace Europa\Core\Autoload;

class Autoloader {
	
	public static function configureAutoload() {
		spl_autoload_register("\Europa\Core\Autoload\Autoloader::loadClass");
	}
	
	public static function loadClass($className) {
		
		$namespaces = explode('\\',$className);
		
		if (!$namespaces) {
			throw new \Europa\Core\Exceptions\CoreException("La classe instanciée n'a pas pu être analysée par Europa\Core\Autoload\Autoloader::loadClass");
		}
		
		$is_namespace = (count($namespaces) - 1) !== 0 ? false : true;
		
		if ($is_namespace) {
			throw new \Europa\Core\Exceptions\CoreException("Vous avez tenté d'instancier une classe non référencée dans Europa par Europa\Core\Autoload\Autoloader::loadClass");
		}
		
		$namespace = '';
		$class = '';
		
		foreach ($namespaces as $k=>$string) {
			if ($k != count($namespaces) - 1) {
				$namespace = $namespace.'\\'.$string;
			} else {
				$class = $string;
			}
		}
		
		if ( !isset(EUROPA_NS_PATH[$namespace])) {
			
			if (count(\Europa\Core\Kernel::$CUSTOM_NAMESPACES)) {
				foreach (\Europa\Core\Kernel::$CUSTOM_NAMESPACES as $ns=>$dir) {
					if ($ns == $namespace) {
						$namespacePath = str_replace("/", DIRECTORY_SEPARATOR, $dir);
						
						chdir("../");
						
						if(!realpath($namespacePath)) {
							throw new \Europa\Core\Exceptions\CoreException("Une erreur est survenue lors du parsing de l'epace de nom ".$namespacePath);

						}
						require_once realpath($namespacePath).DIRECTORY_SEPARATOR.$class.'.php';

						chdir("public");

						return array(
							'namespace'=>$namespace,
							'namespacePath'=>$namespacePath,
							'class'=>$class
						);
					}
				}
				throw new \Europa\Core\Exceptions\CoreException("Vous avez tenté d'instancier une classe dont l'espace de nom n'existe pas dans Europa et n'est pas configuré dans config.json");
			} else {
				throw new \Europa\Core\Exceptions\CoreException("Vous avez tenté d'instancier une classe dont l'espace de nom n'existe pas dans Europa");
			}
		}
		$namespacePath = EUROPA_NS_PATH[$namespace];
		$t = null;
		if (!realpath($namespacePath)) {
			chdir("../");
			$t = 1;
		}
		
//var_dump(getcwd());
//var_dump($namespacePath);
		
		if (realpath($namespacePath)) {
			require_once realpath($namespacePath).DIRECTORY_SEPARATOR.$class.'.php';
		} else {
			require_once $class.'.php';
		}
		
		if (isset($t)) {
			chdir("public");
		}
		
		return array(
			'namespace'=>$namespace,
			'namespacePath'=>$namespacePath,
			'class'=>$class
		);
		
	}
	
}
