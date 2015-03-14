<?php
namespace matacms\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\LoginForm;
use matacms\controllers\base\AuthenticatedController;

/**
 * Site controller
 */
class SiteController extends AuthenticatedController {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionWelcome() {
    	$this->layout = "@matacms/views/layouts/module";
        return $this->render("welcome");
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
