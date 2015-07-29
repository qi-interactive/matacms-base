<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\controllers\user;

class RecoveryController extends \mata\user\controllers\RecoveryController {

	public function actionRequest() {
		$this->layout = "@matacms/views/layouts/login";
		echo parent::actionRequest();
	}

    public function actionReset($id, $code) {
		$this->layout = "@matacms/views/layouts/login";
		echo parent::actionReset($id, $code);
	}

}
