<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */
 
namespace matacms\controllers\user;

class RegistrationController extends \mata\user\controllers\RegistrationController {

	public function actionRegister() {
		$this->layout = "@matacms/views/layouts/login";
		echo parent::actionRegister();
	}

}
