<?php 

namespace matacms\web;

use Yii;

class View extends \yii\web\View {

	public function render($view, $params = [], $context = null) {

		$controller =  Yii::$app->controller;
		$moduleViewFile = $controller->module->getViewPath() . "/" . $controller->id . "/" . $view;

		$particularsView = "@matacms/particulars/" . substr($moduleViewFile, stripos($moduleViewFile, "vendor") + 7);

		try {
			return parent::render($particularsView, $params, $context);
		} catch (\yii\base\InvalidParamException $e) {
			\Yii::info(sprintf("Particulars not found. Search path: %s", $particularsView), 
				\matacms\base\Constants::LOG_CATEGORY);
			
			return parent::render($view, $params, $context);
		}

	}
}