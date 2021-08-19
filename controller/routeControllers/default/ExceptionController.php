<?php

namespace Europa\Controller\DefaultControllers;

class ExceptionController {
	public function status404() {
		http_response_code(404);
	}

}
