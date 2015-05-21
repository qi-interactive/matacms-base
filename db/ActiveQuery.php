<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\db;

use Yii;

class ActiveQuery extends \yii\db\ActiveQuery {

	public function populate($rows) {
		
		$models = parent::populate($rows);

		if ($envModule = Yii::$app->getModule("environment") ) {

			$removedModelsCount = 0;
			$i=0;

			$indexesToUnset = [];

			foreach ($models as &$model) {
				if ($envModule->hasEnvironmentBehavior($model) && $model->getMarkedForRemoval()) {
					$indexesToUnset[] = $i;
					$model = null;
				}

				$i++;
				
			}

			foreach ($indexesToUnset as $index)
				unset($models[$index]);
		}

		return $models;
	}
}
