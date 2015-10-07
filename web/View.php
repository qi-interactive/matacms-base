<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\web;

use Yii;

class View extends \yii\web\View {

	public function render($view, $params = [], $context = null) {

		$controller =  Yii::$app->controller;
		$moduleViewFile = $controller->module->getViewPath() . "/" . $controller->id . "/" . $view;

		$particularsView = "@matacms/modules/" . substr($moduleViewFile, stripos($moduleViewFile, "vendor") + 7);

		if (file_exists(Yii::getAlias($particularsView) . ".php"))
			return parent::render($particularsView, $params, $context);


		if (file_exists($moduleViewFile . ".php")) {

			$view = strpos($moduleViewFile, "vendor") > -1 ?
			"@" . substr($moduleViewFile, stripos($moduleViewFile, "vendor")) :
			"@" .  substr($moduleViewFile, stripos($moduleViewFile, "mata-cms"));

			$view = str_replace("mata-cms", "matacms", $view);

			\Yii::info(sprintf("Module file found. Search path: %s", $view),
				\matacms\base\Constants::LOG_CATEGORY);

			return parent::render($view, $params, $context);
		}

		return parent::render($view, $params, $context);
	}

	public function renderAjax($view, $params = [], $context = null) {

		$controller =  Yii::$app->controller;
		$moduleViewFile = $controller->module->getViewPath() . "/" . $controller->id . "/" . $view;

		$particularsView = "@matacms/modules/" . substr($moduleViewFile, stripos($moduleViewFile, "vendor") + 7);

		if (file_exists(Yii::getAlias($particularsView) . ".php"))
			return parent::renderAjax($particularsView, $params, $context);


		if (file_exists($moduleViewFile . ".php")) {

			$view = strpos($moduleViewFile, "vendor") > -1 ?
			"@" . substr($moduleViewFile, stripos($moduleViewFile, "vendor")) :
			"@" .  substr($moduleViewFile, stripos($moduleViewFile, "mata-cms"));

			$view = str_replace("mata-cms", "matacms", $view);

			\Yii::info(sprintf("Module file found. Search path: %s", $view),
				\matacms\base\Constants::LOG_CATEGORY);

			return parent::renderAjax($view, $params, $context);
		}

		return parent::renderAjax($view, $params, $context);
	}
}
