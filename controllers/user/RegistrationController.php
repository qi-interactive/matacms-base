<?php

namespace matacms\controllers\user;

class RegistrationController extends \mata\user\controllers\RegistrationController {

	public function actionRegister() {
		$this->layout = "@matacms/views/layouts/login";
		echo parent::actionRegister();
	}

}