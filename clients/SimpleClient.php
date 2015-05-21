<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

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

	protected function findAllByAttributes($attributes) {

		$model = $this->getModel();
		$this->closureParams = [$model, $attributes];

		$model = $model::getDb()->cache(function ($db) {
			$closureParams = $this->getClosureParams();
		    return $closureParams[0]->find()->where($closureParams[1])->all();
		}, null, new \matacms\cache\caching\MataLastUpdatedTimestampDependency());

		return $model;
	}


	public function find() {
		$model = $this->getModel();
		return $model->find();
	}

	public function findModel($id) {

		$model = $this->getModel();
		$this->closureParams = [$model, $id];

		$model = $model::getDb()->cache(function ($db) {
			$closureParams = $this->getClosureParams();
		    return $closureParams[0]::findOne($closureParams[1]);
		}, null, new \matacms\cache\caching\MataLastUpdatedTimestampDependency());

		return $model;
	}

	protected function getClosureParams() {
		$retVal = $this->closureParams;
		$this->closureParams = [];
		return $retVal;
	}

}
