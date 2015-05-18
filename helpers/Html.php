<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\helpers;

use yii\helpers\ArrayHelper;
use mata\category\models\Category;
use mata\category\models\CategoryItem;
use mata\tag\models\Tag;
use mata\tag\models\TagItem;
use matacms\widgets\Selectize;
use \mata\widgets\fineuploader\FineUploader;
use mata\media\models\Media;
use yii\helpers\Html as BaseHtml;
use yii\web\View;

class Html extends \yii\helpers\Html {

	public static function activeCategoryField($model, $attribute, $options = []) {

		$items = ArrayHelper::map(Category::find()->grouping($model)->all(), 'Name', 'Name');
		$value = ArrayHelper::getColumn(CategoryItem::find()->with("category")->where(["DocumentId" => $model->getDocumentId()->getId()])->all(), 'category.Name');

		if ($value != null)
			$options["value"] = $value;

		if(!empty($_POST[CategoryItem::REQ_PARAM_CATEGORY_ID]))
			$options["value"] = $_POST[CategoryItem::REQ_PARAM_CATEGORY_ID];

		$options["name"] = CategoryItem::REQ_PARAM_CATEGORY_ID;

		$options['id'] = self::getInputId($model, $attribute);

		$prompt = 'Select ' . $model->getAttributeLabel($attribute);
        if(isset($options['prompt'])) {
            $prompt = $options['prompt'];
            unset($options['prompt']);
        }

		$options = ArrayHelper::merge([
			'items' => $items,
			'options' => ['multiple'=>true, 'prompt' => $prompt],
			'clientOptions' => [
			'plugins' => ["remove_button", "drag_drop", "restore_on_backspace"],
			'create' => false,
			'persist' => false,
			]
			], $options);

		return Selectize::widget($options);
	}

	public static function activeTagField($model, $attribute, $options = []) {

		$items = ArrayHelper::map(Tag::find()->orderBy('Name ASC')->all(), 'Name', 'Name');
		$value = ArrayHelper::getColumn(TagItem::find()->with("tag")->where(["DocumentId" => $model->getDocumentId()->getId()])->all(), 'tag.Name');

		if ($value != null)
			$options["value"] = $value;

		$options["name"] = TagItem::REQ_PARAM_TAG_ID;

		$prompt = 'Select ' . $model->getAttributeLabel($attribute);
        if(isset($options['prompt'])) {
            $prompt = $options['prompt'];
            unset($options['prompt']);
        }

		$options = ArrayHelper::merge([
			'items' => $items,
			'options' => ['multiple'=>true, 'prompt' => $prompt],
			'clientOptions' => [
			'plugins' => ["remove_button", "drag_drop", "restore_on_backspace"],
			'create' => true,
			'persist' => false,
			]
			], $options);

		return Selectize::widget($options);
	}

	public static function activeMediaField($model, $attribute, $options = []) {
		return FineUploader::widget([
			'name' => $model->getDocumentId()->getId(),
			'model' => $model,
			'attribute' => $attribute,
			'options' => $options,
			'uploadSuccessEndpoint' => "/mata-cms/media/s3/upload-successful?documentId=" . urlencode($model->getDocumentId($attribute))
			]);
	}

	public static function dropDownList($name, $selection = null, $items = [], $options = [])
    {
        if (!empty($options['multiple'])) {
            return static::listBox($name, $selection, $items, $options);
        }
        $options['name'] = $name;
        unset($options['unselect']);
        $selectOptions = static::renderSelectOptions($selection, $items, $options);
        return static::tag('select', "\n" . $selectOptions . "\n", $options);
    }

    /*
     * Render select options based on value order
     */

    public static function renderSelectOptions($selection, $items, &$tagOptions = [])
    {
        $lines = [];
        $encodeSpaces = ArrayHelper::remove($tagOptions, 'encodeSpaces', false);
        $encode = ArrayHelper::remove($tagOptions, 'encode', true);
        if (isset($tagOptions['prompt'])) {
            $prompt = $encode ? static::encode($tagOptions['prompt']) : $tagOptions['prompt'];
            if ($encodeSpaces) {
                $prompt = str_replace(' ', '&nbsp;', $prompt);
            }
            $lines[] = static::tag('option', $prompt, ['value' => '']);
        }

        $options = isset($tagOptions['options']) ? $tagOptions['options'] : [];
        $groups = isset($tagOptions['groups']) ? $tagOptions['groups'] : [];
        unset($tagOptions['prompt'], $tagOptions['options'], $tagOptions['groups']);
        $options['encodeSpaces'] = ArrayHelper::getValue($options, 'encodeSpaces', $encodeSpaces);
        $options['encode'] = ArrayHelper::getValue($options, 'encode', $encode);

        if(is_array($selection)) {

        	foreach($selection as $selectedKey => $selectedValue) {

        		if(ArrayHelper::keyExists($selectedValue, $items)) {
        			$item = $items[$selectedValue];
        			$attrs = isset($options[$selectedValue]) ? $options[$selectedValue] : [];
	                $attrs['value'] = (string) $selectedValue;
	                $attrs['selected'] = true;
        			$text = $encode ? static::encode($selectedValue) : $selectedValue;
        			$lines[] = static::tag('option', $item, $attrs);
        		}
        	}

        	foreach ($items as $key => $value) {

	            if (is_array($value)) {
	                $groupAttrs = isset($groups[$key]) ? $groups[$key] : [];
	                $groupAttrs['label'] = $key;
	                $attrs = ['options' => $options, 'groups' => $groups, 'encodeSpaces' => $encodeSpaces, 'encode' => $encode];
	                $content = static::renderSelectOptions($selection, $value, $attrs);
	                $lines[] = static::tag('optgroup', "\n" . $content . "\n", $groupAttrs);
	            } else {
	            	if(!in_array($key, $selection)) {
	            		$attrs = isset($options[$key]) ? $options[$key] : [];
		                $attrs['value'] = (string) $key;
		                $attrs['selected'] = $selection !== null &&
		                        (!is_array($selection) && !strcmp($key, $selection)
		                        || is_array($selection) && in_array($key, $selection));
		                $text = $encode ? static::encode($value) : $value;
		                if ($encodeSpaces) {
		                    $text = str_replace(' ', '&nbsp;', $text);
		                }
		                $lines[] = static::tag('option', $text, $attrs);
	            	}
	                
	            }
	        }

        } else {
        	foreach ($items as $key => $value) {
	            if (is_array($value)) {
	                $groupAttrs = isset($groups[$key]) ? $groups[$key] : [];
	                $groupAttrs['label'] = $key;
	                $attrs = ['options' => $options, 'groups' => $groups, 'encodeSpaces' => $encodeSpaces, 'encode' => $encode];
	                $content = static::renderSelectOptions($selection, $value, $attrs);
	                $lines[] = static::tag('optgroup', "\n" . $content . "\n", $groupAttrs);
	            } else {
	                $attrs = isset($options[$key]) ? $options[$key] : [];
	                $attrs['value'] = (string) $key;
	                $attrs['selected'] = $selection !== null &&
	                        (!is_array($selection) && !strcmp($key, $selection)
	                        || is_array($selection) && in_array($key, $selection));
	                $text = $encode ? static::encode($value) : $value;
	                if ($encodeSpaces) {
	                    $text = str_replace(' ', '&nbsp;', $text);
	                }
	                $lines[] = static::tag('option', $text, $attrs);
	            }
	        }
        }

        return implode("\n", $lines);
    }

    public static function submitButton($content = 'Submit', $options = [])
    {
    	$containerId = uniqid("form-submit");

		$retVal = BaseHtml::beginTag("div", [
			"id" => $containerId,
			"class" => "form-group submit-form-group"
			]);

		$options['type'] = 'submit';
        $retVal .= static::button($content, $options);

		$retVal .= BaseHtml::endTag("div");

		$formId = $options['formId'];

		\Yii::$app->view->registerJs("			
			var form = $('#$formId'),
			isSubmitted = false;
	
			form.on('submit', function(e) {
				if(!isSubmitted) {
					isSubmitted = form.yiiActiveForm('submitForm');
					return isSubmitted;
				}	
				$('#$containerId button').attr('disabled', 'disabled');
				return false;			
			}); 
		", View::POS_READY);

        return $retVal;
    }
}
