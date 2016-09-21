<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\controllers\base;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Perhaps we should move this to mataframework? media/redactor controller would need this!
 **/

abstract class AuthenticatedController extends \yii\web\Controller {

    public function actions()
    {
        return [
            'error' => [
                'class' => 'matacms\actions\ErrorAction',
            ]
        ];
    }

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
                    [
						'allow' => false,
						'roles' => ['?'],
					],
					[
						'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return $action->controller->module->id == 'app-mata';
                        }
					],
                    [
						'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $menuModules = ArrayHelper::getColumn(\Yii::$app->moduleAccessibilityManager->getAvailableModules(), 'ModuleId');
                            $menuModules = array_map('mb_strtolower', $menuModules);
                            $actionModuleId = mb_strtolower($action->controller->module->id);
                            $hasAccess = in_array($actionModuleId, $menuModules);

                    		$modulesAccessibleForUser = ArrayHelper::getColumn(\Yii::$app->moduleAccessibilityManager->getModulesByUser(Yii::$app->user->getId()), 'ModuleId');
                            $modulesAccessibleForUser = array_map('mb_strtolower', $modulesAccessibleForUser);

                            $hasAccess = \Yii::$app->user->identity->getIsAdmin() || in_array($actionModuleId, $modulesAccessibleForUser);

                            return $hasAccess;
                        },
                        'denyCallback' => function ($rule, $action) {
                            throw new \Exception('You are not allowed to access this page');
                        }
					],
                    [
						'allow' => false,
                        'matchCallback' => function ($rule, $action) {
                            $menuModules = ArrayHelper::getColumn(\Yii::$app->moduleAccessibilityManager->getAvailableModules(), 'ModuleId');
                            $menuModules = array_map('mb_strtolower', $menuModules);
                            $actionModuleId = mb_strtolower($action->controller->module->id);
                            $hasAccess = in_array($actionModuleId, $menuModules);

                    		$modulesAccessibleForUser = ArrayHelper::getColumn(\Yii::$app->moduleAccessibilityManager->getModulesByUser(Yii::$app->user->getId()), 'ModuleId');
                            $modulesAccessibleForUser = array_map('mb_strtolower', $modulesAccessibleForUser);
                            $hasAccess = \Yii::$app->user->identity->getIsAdmin() || in_array($actionModuleId, $modulesAccessibleForUser);

                            return !$hasAccess;
                        },
                        'denyCallback' => function ($rule, $action) {
                            throw new ForbiddenHttpException(\Yii::t('yii', 'You have no access to this module.'));
                        }
					],
				],
			]
		];
	}

}
