<?php

namespace matacms\controllers\base;

use yii\filters\AccessControl;

/** 
 * Perhaps we should move this to mataframework? media/redactor controller would need this!
 **/

abstract class AuthenticatedController extends \yii\web\Controller {
	public function behaviors() {
		return [
		'access' => [
		'class' => AccessControl::className(),
		'rules' => [
		[
		'allow' => true,
		'roles' => ['@'],
		],
		],
		]
		];
	}
}