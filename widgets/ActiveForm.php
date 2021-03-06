<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\widgets;

use Yii;
use yii\base\Event;
use matacms\base\ActiveFormMessage;

class ActiveForm extends \yii\widgets\ActiveForm {

	public $fieldClass = 'matacms\widgets\ActiveField';
	private $fieldIndex = 0;

	const EVENT_FIELD_GENERATED = "EVENT_FIELD_GENERATED";

	public function field($model, $attribute, $options = []) {

	   $retVal = parent::field($model, $attribute, $options);
	   Event::trigger(self::class, self::EVENT_FIELD_GENERATED, new ActiveFormMessage($this, $model, $this->fieldIndex++));
	   return $retVal;
	}

	private function processSave($model) {
		if (empty($categoryId = Yii::$app->request->post(CategoryItem::REQ_PARAM_CATEGORY_ID)))
			return;

		$documentId = $model->getDocumentId()->getId();

		CategoryItem::deleteAll([
			"DocumentId" => $documentId
			]);

		$categoryItem = new CategoryItem();
		$categoryItem->attributes = [
			"CategoryId" => $categoryId,
			"DocumentId" => $documentId
		];

		try {
			if ($categoryItem->save() == false)
				throw new \yii\web\ServerErrorHttpException($categoryItem->getTopError());
		} catch(yii\db\IntegrityException $e) {
			// Create the missing category
			$categoryItem->save();
		}
	}

	public function submitButton($model, $content = null, $options = ['class' => 'btn btn-primary']) {

		$content = $content != null ? $content : $model->isNewRecord ? "Save" : "Update";

		$options['formId'] = $this->getId();

		$htmlClass = \matacms\helpers\Html::class;

		if(Yii::$app->hasModule("environment")) {
			$module = \Yii::$app->getModule("environment");
			$htmlClass = ($module->hasEnvironmentBehavior($model)) ? \matacms\environment\helpers\Html::class : \matacms\helpers\Html::class;
		}

		return $htmlClass::submitButton($content, $options);
	}

}
