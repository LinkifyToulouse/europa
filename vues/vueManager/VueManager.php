<?php

namespace Europa\Vue;

class VueManager {
	
	public $attributes = array();
	public $URLattributes = array();
	
	public function __construct($fileName, $fileParameters) {
		
		if (!isset($fileParameters)) {
			$fileParameters = array(
				"TEMPLATE_EXTENSION"=>"php"
			);
		}
		$this->attributes = $fileParameters;
		unset($this->attributes["TEMPLATE_EXTENSION"]);
		
		$this->URLattributes = \Europa\Core\RouteHandler::$URLattributes;
		
		chdir("../");
		$tagsDir = realpath("controller".DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR);
		
		chdir("public");
		
		if (\Europa\Core\Kernel::$OPTIONS['AUTOLOAD_COMPONENTS']) {
			$components = new \Europa\Vue\ComponentsManager\ComponentsManager(array(
				'tag_directory' 		=> $tagsDir,
				'template_directory' 	=> $tagsDir
			));
		}
		
		chdir("../");
		include("vues/templates/$fileName.".$fileParameters["TEMPLATE_EXTENSION"]);
		
		chdir("public");
		
		if (\Europa\Core\Kernel::$OPTIONS['AUTOLOAD_COMPONENTS']) {
			$components->parse();
		}
	}
	
	public function include($vue, array $fileParameters = array(
				"TEMPLATE_EXTENSION"=>"php"
			)) {
		//chdir("../");
			include("vues/templates/$vue.".$fileParameters["TEMPLATE_EXTENSION"]);
		
		//chdir("public");
	}
	
	public function loremIpsum(string $type = 'P', $length = 1) {
		if ($type == 'P') {
			$rtn = "<p>Do malis proident, dolore probant e pariatur. Nulla cernantur instituendarum te 
an magna amet se iudicem. Ab legam nostrud despicationes. Eram iudicem nam 
voluptate ne nescius in sunt. Labore laborum cernantur. Qui voluptate 
consectetur, incididunt de veniam, fore officia ex sempiternum, eram o appellat. 
Labore de eiusmod ita tamen ex do de dolor deserunt qui aut o malis pariatur id 
noster ut voluptate a dolor a aut te duis admodum, a tamen nescius distinguantur 
ita sint quamquam doctrina do ex iis quem tamen magna.</p>";
		} else if ($type == 'S') {
			$rtn = "Incididunt imitarentur quo voluptate a do iis comprehenderit. ";
		} else {
			$rtn = "Quorum an incurreret nam multos id sed laborum reprehenderit. Quamquam eram amet o quorum, hic culpa ingeniis. De quid occaecat vidisse, quo legam irure cillum mandaremus. Quibusdam fidelissimae do fabulas nam aut senserit ne iudicem. Sint ut ab fugiat admodum, ab de tamen quorum quid. ";
		}
		echo str_repeat($rtn,$length);
	}
	
	public function get($val) {
		return $this->attributes[$val];
	}
	public function getURLParameter($val) {
		return $this->URLattributes[$val];
	}
}
