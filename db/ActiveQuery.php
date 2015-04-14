<?php

namespace matacms\db;

use Yii;

class ActiveQuery extends \yii\db\ActiveQuery {

	public function populate($rows) {
		
		$models = parent::populate($rows);

		if ($envModule = Yii::$app->getModule("environment") ) {

			$removedModelsCount = 0;
			$i=0;

			foreach ($models as &$model) {
				if ($envModule->hasEnvironmentBehavior($model) && $model->getMarkedForRemoval()) {
					$model = null;
					unset($models[$i]);
					$i++;
				}
			}
		}

		return $models;
	}

		
}


?>