<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\controllers\user;

class SecurityController extends \mata\user\controllers\SecurityController {

	public function actionLogin() {
		$this->layout = "@matacms/views/layouts/login";
		echo parent::actionLogin();
	}

}
