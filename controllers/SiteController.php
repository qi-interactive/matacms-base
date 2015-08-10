<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\LoginForm;
use matacms\controllers\base\AuthenticatedController;

class SiteController extends AuthenticatedController {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionWelcome() {
    	$this->layout = "@matacms/views/layouts/module";
        return $this->render("welcome");
    }

}
