<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\controllers\module;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mata\helpers\BehaviorHelper;
use matacms\filters\NotificationFilter;
use matacms\base\MessageEvent;
use yii\filters\AccessControl;
use matacms\controllers\base\AuthenticatedController;
use yii\data\Sort;

abstract class Controller extends AuthenticatedController {

	const EVENT_MODEL_CREATED = "EVENT_MODEL_CREATED";
	const EVENT_MODEL_UPDATED = "EVENT_MODEL_UPDATED";
	const EVENT_MODEL_DELETED = "EVENT_MODEL_DELETED";

	public function behaviors() {
		return [
		'notifications' => [
		'class' => NotificationFilter::className(),
		]
		];
	}

	public function actions() {
		return [
		'history' => [
			'class' => 'mata\arhistory\actions\HistoryAction',
			'view' => '@matacms/views/history/history'
		]
		];
	}

	public function actionCreate() {
		$model = $this->getModel();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->trigger(self::EVENT_MODEL_CREATED, new MessageEvent($model));

			return $this->redirect(['index', reset($model->getTableSchema()->primaryKey) => $model->getPrimaryKey()]);
		} else {
			return $this->render("create", [
				'model' => $model,
				]);
		}
	}

	public function actionUpdate($id) {

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->trigger(self::EVENT_MODEL_UPDATED, new MessageEvent($model));
			return $this->redirect(['index', reset($model->getTableSchema()->primaryKey) => $model->getPrimaryKey()]);
		} else {

			return $this->render("update", [
				'model' => $model,
				]);
		}
	}

	public function actionDelete($id) {

		$model = $this->findModel($id);
		$model->delete();
		$this->trigger(self::EVENT_MODEL_DELETED, new MessageEvent($model));

		return $this->redirect(['index']);
	}

	public function render($view, $params = []) {

		try {
			return parent::render($view, $params);
		} catch (\yii\base\InvalidParamException $e) {
			// view not found using default Yii routing
			return $this->renderMataCmsView($view, $params);
		}
	}

	private function renderMataCMSView($view, $params) {

		$moduleViewFile = Yii::$app->controller->module->getViewPath() . "/" . $this->id . "/" . $view;

		if (file_exists($moduleViewFile . ".php")) {
			$view = strpos($moduleViewFile, "vendor") > -1 ? 
			"@" . substr($moduleViewFile, stripos($moduleViewFile, "vendor")) : 
			"@" .  substr($moduleViewFile, stripos($moduleViewFile, "mata-cms"));
		} else {
			$view =  "@vendor/matacms/matacms-base/views/module/" . $view;
		}

		return parent::render($view, $params);
	}

	public function actionIndex() {

		$searchModel = $this->getSearchModel();
		$searchModel = new $searchModel();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$sort = new Sort([
			'attributes' => $searchModel->filterableAttributes()
		]);

		if(!empty($sort->orders)) {
			$dataProvider->query->orderBy = null;
		} else {
			if(BehaviorHelper::hasBehavior($searchModel, \mata\arhistory\behaviors\HistoryBehavior::class)) {
				$dataProvider->query->select('*');
				$reflection =  new \ReflectionClass($searchModel);
				$parentClass = $reflection->getParentClass();

				$alias = $searchModel->getTableSchema()->name;
				$pk = $searchModel->getTableSchema()->primaryKey;


				if (is_array($pk)) {
					if(count($pk) > 1)
						throw new NotFoundHttpException('Combined primary keys are not supported.');
					$pk = $pk[0];
				}

				$aliasWithPk = $alias . '.' . $pk;

				$dataProvider->query->join('INNER JOIN', 'arhistory_revision', 'arhistory_revision.DocumentId = CONCAT(:class, '.$aliasWithPk.')', [':class' => $parentClass->name . '-']);
	        	$dataProvider->query->andWhere('arhistory_revision.Revision = (SELECT MAX(Revision) FROM `arhistory_revision` WHERE arhistory_revision.`DocumentId` = CONCAT(:class, '.$aliasWithPk.'))', [':class' => $parentClass->name . '-']);
	        	$dataProvider->query->orderBy('arhistory_revision.DateCreated DESC');
			}	
		}
		 
		$dataProvider->setSort($sort);

		return $this->render("index", [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'sort' => $sort
			]);
	}


	protected function findModel($pk) {

		$model = $this->getModel();

		if (($model = $model::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	protected abstract function getModel();
	protected abstract function getSearchModel();
	
}
