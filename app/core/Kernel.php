<?php

namespace Europa\Core;

class Kernel {
	
	const LOADING_TYPE = 'LOADING_TYPE';
	const KERNEL_INIT_EUROPA = 'KERNEL_INIT_EUROPA';
	const KERNEL_DEFAULT_LOADING = 'KERNEL_DEFAULT_LOADING';
	
	
	private $_isCoreRequired = false;
	
	public static $ENV = null;
	public static $DOMAINS = array();
	public static $CUSTOM_NAMESPACES = array();
	public static $REDIRECT_RULES = array();
	public static $DATABASE = array(
		'host' => false,
		'port' => false,
		'username' => false,
		'password' => false,
		'database' => false);
	public static $OPTIONS = array();
	
	public function __construct($options = array(
		self::LOADING_TYPE=>self::KERNEL_DEFAULT_LOADING,
		"AUTOLOAD_COMPONENTS"=>true
	)) {
		self::$OPTIONS = $options;
		
		$this->_initCore();
		$this->_loadConfig();
		
		$this->_configureEnv();
		$this->_initEuropa($options[self::LOADING_TYPE], self::$REDIRECT_RULES);
		$this->_initDatabase(self::$DATABASE);
		
		RequestHandler::init(true);
		
	}
	
	private function _requireCore() {
		if (!$this->_isCoreRequired) {
			require_once 'constants/ConstantsDefinitions.php';
			require_once 'autoload/Autoloader.php';
			require_once 'exceptions/ExceptionsDefinition.php';
			$this->_isCoreRequired = true;
		}
	}
	
	private function _initCore() {
		$this->_requireCore();
		\Europa\Core\Constants\ConstantsDefinition::defineConstants();
		\Europa\Core\Autoload\Autoloader::configureAutoload();
	}
	
	private function _loadConfig() {
		$tmp_config = file_get_contents(EUROPA_DEFAULT_PATH.'app/config/config.json');
		$tmp_config = json_decode($tmp_config, TRUE);
		$config = $tmp_config['config'];
		
		if (isset($config['domains'])) {
			if (!count($config['domains'])) {
				throw new \Europa\Core\Exceptions\CoreException("Aucun domaine n'est correctement défini dans config.json");
			}
			self::$DOMAINS = $config['domains'];
		} else {
			throw new \Europa\Core\Exceptions\CoreException("Aucun domaine n'est correctement défini dans config.json");
		}
		
		if (isset($config['redirectRules'])) {
			self::$REDIRECT_RULES = $config['redirectRules'];
		}
		
		if (isset($config['env'])) {
			self::$ENV = $config['env'] == EUROPA_ENV_DEV ? EUROPA_ENV_DEV : EUROPA_ENV_PROD;
		} else {
			throw new \Europa\Core\Exceptions\CoreException("L'environnement n'est pas défini dans config.json");
		}
		
		if (isset($config['database'])) {
			self::$DATABASE = $config['database'];
		}
		
		if (isset($config['namespaces'])) {
			self::$CUSTOM_NAMESPACES = $config['namespaces'];
		}
		
	}
	
	private function _configureEnv() {
		if (self::$ENV === EUROPA_ENV_PROD) {
			ini_set('error_reporting', 0);
			ini_set('track_errors', '0');
			ini_set('display_errors', '0');
			ini_set('display_startup_errors', '0');
		} else {
			ini_set('error_reporting', E_ALL);
			ini_set('track_errors', '1');
			ini_set('display_errors', '1');
			ini_set('display_startup_errors', '1');
		}
	}
	
	private function _initEuropa($option, $REDIRECT_RULES) {
		if ($option === self::KERNEL_INIT_EUROPA) {
			
			$rules = '';
			foreach ($REDIRECT_RULES as $k=>$rule) {
				$rules .= "RewriteCond %{REQUEST_URI} !".$rule['rule']."\n";
			}
			
			$htaccessText = "Options +FollowSymlinks\n
RewriteEngine on\n

RewriteCond %{REQUEST_URI} !europa.php(.*)\n
".$rules."
RewriteRule ([\w\d\.\-\#\?\:\!\=\_\/\@]*) europa.php?_req=$1";
			
			file_put_contents(".htaccess",$htaccessText);
		}
		
		/*
Options +FollowSymlinks
RewriteEngine on

RewriteCond %{REQUEST_URI} !index.php(.*)
RewriteRule ([\w\d\.\-\#\?\:\!\=\_\/\@]*) europa.php?_req=$1
		*/
	}
	
	private function _initDatabase($dbParameters) {
		
		if (
			!isset($dbParameters['host']) ||
			!isset($dbParameters['port']) ||
			!isset($dbParameters['username']) ||
			!isset($dbParameters['password']) ||
			!isset($dbParameters['database'])
		) {
			throw new \Europa\Core\Exceptions\CoreException("Veuillez définir un objet 'database' dans config.json");
		}
		
		if ($dbParameters['host'] === false || $dbParameters['username'] === false || $dbParameters['password'] === false || $dbParameters['database'] === false) {
			DatabaseManager::setError();
		}
		
		if (isset($dbParameters['autoLoad'])) {
			if ($dbParameters['autoLoad'] === true) {
				DatabaseManager::init();
			}
		}
		
	}
	
}
