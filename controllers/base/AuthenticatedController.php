<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

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
