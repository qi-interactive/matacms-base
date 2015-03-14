<?php

namespace matacms\controllers\user;



class SecurityController extends \mata\user\controllers\SecurityController {


	public function actionLogin() {
		$this->layout = "@matacms/views/layouts/login";
		echo parent::actionLogin();
	}


}