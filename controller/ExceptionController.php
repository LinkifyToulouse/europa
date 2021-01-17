<?php
namespace Controller {
	require 'DefaultController.php';
	class ExceptionController extends DefaultController {
		
		public function send404error() {
			http_response_code(404);
		}
		
	}
	
}
