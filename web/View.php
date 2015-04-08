<?php 

namespace matacms\web;

use Yii;

class View extends \yii\web\View {

	public function render($view, $params = [], $context = null) {

		$controller =  Yii::$app->controller;
		$moduleViewFile = $controller->module->getViewPath() . "/" . $controller->id . "/" . $view;

		if (file_exists($moduleViewFile . ".php")) {

			$view = strpos($moduleViewFile, "vendor") > -1 ? 
			"@" . substr($moduleViewFile, stripos($moduleViewFile, "vendor")) : 
			"@" .  substr($moduleViewFile, stripos($moduleViewFile, "mata-cms"));
			
			$view = str_replace("mata-cms", "matacms", $view);
			return parent::render($view, $params, $context);
		}

		try {
			\Yii::info(sprintf("Module file not found. Search path: %s", $moduleViewFile), 
				\matacms\base\Constants::LOG_CATEGORY);

			
			$particularsView = "@matacms/particulars/" . substr($moduleViewFile, stripos($moduleViewFile, "vendor") + 7);
			return parent::render($particularsView, $params, $context);
		} catch (\yii\base\InvalidParamException $e) {
			\Yii::info(sprintf("Particulars not found. Search path: %s", $particularsView), 
				\matacms\base\Constants::LOG_CATEGORY);

			return parent::render($view, $params, $context);
		}
	}
}