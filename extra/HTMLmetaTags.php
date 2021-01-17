<?php
namespace Extra {
	class HTMLmetaTags {
		public static $CONTROLLER = null;
		
		public static function setController($controller):bool {
			self::$CONTROLLER = $controller;
			return true;
		}
		
		public static function getTitle() {
			if (self::isControllerSet()) {
				return self::$CONTROLLER::$TEMPLATE_PARAMETERS['title'];
			} else {
				return false;
			}
		}
		
		public static function getSEOTitle() {
			if (self::isControllerSet()) {
				return self::$CONTROLLER::$TEMPLATE_PARAMETERS['SEOtitle'];
			} else {
				return false;
			}
		}
		
		public static function getDescription() {
			if (self::isControllerSet()) {
				return self::$CONTROLLER::$TEMPLATE_PARAMETERS['description'];
			} else {
				return false;
			}
		}
		
		public static function getSEODescription() {
			if (self::isControllerSet()) {
				return self::$CONTROLLER::$TEMPLATE_PARAMETERS['SEOdescription'];
			} else {
				return false;
			}
		}
		
		public static function getCanonicalURL() {
			if (self::isControllerSet()) {
				return self::$CONTROLLER::$TEMPLATE_PARAMETERS['canonicalURL'];
			} else {
				return false;
			}
		}
		
		public static function getKeywords() {
			if (self::isControllerSet()) {
				return self::$CONTROLLER::$TEMPLATE_PARAMETERS['keywordsSEO'];
			} else {
				return false;
			}
		}
		
		public static function getRobotsSEO() {
			if (self::isControllerSet()) {
				$v = '';
				if (self::$CONTROLLER::$TEMPLATE_PARAMETERS['robotsIndexSEO']) {
					$v = 'index;';
				}else{
					$v = 'noindex;';
				}
				if (self::$CONTROLLER::$TEMPLATE_PARAMETERS['robotsFollowSEO']) {
					$v=$v.'follow';
				}else{
					$v=$v.'nofollow';
				}
				return $v;
			} else {
				return false;
			}
		}
		
		public static function isControllerSet($autowire = true):bool {
			if (self::$CONTROLLER !== null) {
				return true;
			} else {
				if ($autowire) {
					self::$CONTROLLER = \App\Kernel\RouteHandler::$CONTROLLER;
					return true;
				} else {
					return false;
				}
			}
		}
	}
}
