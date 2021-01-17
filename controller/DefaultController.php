<?php

namespace Controller {
	
	class DefaultController {
		protected $parameters = [];
		public static $TEMPLATE_PARAMETERS = ['canonicalURL'=>''];
		
		public function __construct($par) {
			$this->parameters = $par;
		}
		
		public function render($url) {
			\App\Kernel\Response::send($url.'.php');
		}
		
		public function redirect($url) {
			\App\Kernel\Response::redirect($url.'.php');
		}
		
		public function setHeader($header) {
			\App\Kernel\Response::setHeader($header);
		}
		
		public function display(string $text) {
			\App\Kernel\Response::display($text);
		}
		
		public function getUrlParams(array $scheme, $face = null) {
			if (isset($this->parameters['face'])) {
				$face = '#^'.$this->parameters['face'].'$#';
			}
			$parsedUrl = [];
			foreach ($scheme as $k => $v) {
				$n = $k+1;
				$parsedUrl[$v] = preg_replace($face, '$'.$n, \App\Kernel\Request::$requestRaw);
			}
			return $parsedUrl;
		}
		
		public function hydrateTemplate(array $params) {
			self::$TEMPLATE_PARAMETERS = \array_merge(self::$TEMPLATE_PARAMETERS,$params);
		}
	}
	
}
