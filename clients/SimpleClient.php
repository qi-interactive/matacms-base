<?php 

namespace matacms\clients;

abstract class SimpleClient {

	protected abstract function getModel();
	protected $closureParams = [];

	protected function findByAttributes($attributes) {

		$model = $this->getModel();
		$this->closureParams = [$model, $attributes];

		$model = $model::getDb()->cache(function ($db) {
			$closureParams = $this->getClosureParams();
		    return $closureParams[0]->find()->where($closureParams[1])->one();
		}, null, new \matacms\cache\caching\MataLastUpdatedTimestampDependency());

		return $model;
	}

	public function findModel($id) {

		$model = $this->getModel();
		$this->closureParams = [$model, $id];

		$model = $model::getDb()->cache(function ($db) {
			$closureParams = $this->getClosureParams();
		    return $closureParams[0]::findOne($closureParams[1]);
		}, null, new \matacms\cache\caching\MataLastUpdatedTimestampDependency());

		if ($model !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	protected function getClosureParams() {
		$retVal = $this->closureParams;
		$this->closureParams = [];
		return $retVal;
	}

}