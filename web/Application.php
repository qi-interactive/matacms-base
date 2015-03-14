<?php

namespace matacms\web;

class Application extends \mata\web\Application {
	
	public $modelMap = [];

	// public function init() {
	// 	parent::init();

	// 	// $this->parseModelMap();
	// }


	private function parseModelMap() {

		foreach ($this->modelMap as $class => $definition) {
		    \Yii::$container->set($class, $definition);
		    $modelName = is_array($definition) ? $definition['class'] : $definition;
		    // $module->modelMap[$name] = $modelName;
		}

	}
}