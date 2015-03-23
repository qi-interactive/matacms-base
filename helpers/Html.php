<?php

namespace matacms\helpers;

use yii\helpers\ArrayHelper;
use mata\category\models\Category;
use mata\category\models\CategoryItem;
use mata\tag\models\Tag;
use mata\tag\models\TagItem;
use yii\selectize\Selectize;
use \mata\widgets\fineuploader\Fineuploader;
use mata\media\models\Media;

class Html extends \yii\helpers\Html {

	public static function activeCategoryField($model, $options = []) {

		$items = ArrayHelper::map(Category::find()->grouping($model)->all(), 'Name', 'Name');
		$value = ArrayHelper::getColumn(CategoryItem::find()->with("category")->where(["DocumentId" => $model->getDocumentId()])->all(), 'category.Name');

		if ($value != null)
			$options["value"] = $value;

		$options["name"] = CategoryItem::REQ_PARAM_CATEGORY_ID;

		$options = ArrayHelper::merge([
			'items' => $items,
			'options' => ['multiple'=>true],
			'clientOptions' => [
			'plugins' => ["remove_button", "drag_drop", "restore_on_backspace"],
			'create' => false,
			'persist' => false,
			]
			], $options);

		return Selectize::widget($options);
	}

	public static function activeTagField($model, $options = []) {

		$items = ArrayHelper::map(Tag::find()->all(), 'Name', 'Name');
		$value = ArrayHelper::getColumn(TagItem::find()->with("tag")->where(["DocumentId" => $model->getDocumentId()])->all(), 'tag.Name');

		if ($value != null)
			$options["value"] = $value;

		$options["name"] = TagItem::REQ_PARAM_TAG_ID;

		$options = ArrayHelper::merge([
			'items' => $items,
			'options' => ['multiple'=>true],
			'clientOptions' => [
			'plugins' => ["remove_button", "drag_drop", "restore_on_backspace"],
			'create' => true,
			'persist' => false,
			]
			], $options);

		return Selectize::widget($options);
	}

	public static function activeMediaField($model, $attribute, $options = []) {
		return Fineuploader::widget([
			'name' => $model->getDocumentId(),
			'model' => $model,
			'attribute' => $attribute,
			'options' => $options,
			'uploadSuccessEndpoint' => "/mata-cms/media/s3/upload-successful?documentId=" . urlencode($model->getDocumentId($attribute))
			]);

	}
}