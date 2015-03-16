<?php

namespace matacms\controllers\module;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use matacms\filters\NotificationFilter;
use matacms\base\MessageEvent;
use yii\filters\AccessControl;
use matacms\controllers\base\AuthenticatedController;

abstract class Controller extends AuthenticatedController {

	const EVENT_MODEL_CREATED = "EVENT_MODEL_CREATED";
	const EVENT_MODEL_UPDATED = "EVENT_MODEL_UPDATED";
	const EVENT_MODEL_DELETED = "EVENT_MODEL_DELETED";

	public function behaviors() {
		return [
		'verbs' => [
		'class' => VerbFilter::className(),
		'actions' => [
		'delete' => ['post'],
		],
		],
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
		$model = new $model;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->trigger(self::EVENT_MODEL_CREATED, new MessageEvent($model));

			return $this->redirect(['index', reset($model->getTableSchema()->primaryKey) => $model->getPrimaryKey()]);
		} else {
			return $this->render($this->findView(), [
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

			return $this->render($this->findView(), [
				'model' => $model,
				]);
		}
	}

	public function actionDelete($id) {

		$model = $this->findModel($id);
		$label = $model->getLabel();
		$model->delete();

		$this->trigger(self::EVENT_MODEL_DELETED, new MessageEvent($label));
		return $this->redirect(['index']);
	}

	public function findView($view = null) {

		$view =  $view ?: $this->action->id;
		$moduleViewFile = Yii::$app->controller->module->getViewPath() . "/" . $this->id . "/" . $view;

		if (file_exists($moduleViewFile . ".php")) {
			$view = strpos($moduleViewFile, "vendor") > -1 ? 
			"@" . substr($moduleViewFile, stripos($moduleViewFile, "vendor")) : 
			"@" .  substr($moduleViewFile, stripos($moduleViewFile, "mata-cms"));
		} else {
			$view =  "@matacms/views/module/" . $view;
		}

		return $view;
	}

	public function actionIndex() {

		$searchModel = $this->getSearchModel();
		$searchModel = new $searchModel();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render($this->findView(), [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
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

