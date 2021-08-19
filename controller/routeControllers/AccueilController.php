<?php

namespace Europa\Controller;

class AccueilController {
	public function accueil() {
		\Europa\Core\ResponseHandler::render("welcome");
	}
}
