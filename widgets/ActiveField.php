<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\widgets;

use Yii;
use yii\helpers\Json;
use yii\base\Event;
use mata\base\MessageEvent;
use matacms\widgets\Selectize;
use zhuravljov\widgets\DatePicker;
use mata\widgets\DateTimePicker\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\helpers\Inflector;
use matacms\settings\models\Setting;

class ActiveField extends \yii\widgets\ActiveField {

	public $model;

    const EVENT_INIT_DONE = "matacms\widgets\ActiveField::EVENT_INIT_DONE";

    const SETTING_SHOW_FIELD = "show-field";

    public function init() {
        Event::trigger(self::className(), self::EVENT_INIT_DONE, new MessageEvent($this));
    }

    public function render($content = null) {
        if($this->model instanceof \mata\db\ActiveRecord) {
            if (Setting::findValue($this->model->getDocumentId($this->attribute, self::SETTING_SHOW_FIELD)->getIdNoPk()) !== false &&
                Setting::findValue($this->model->getDocumentId($this->attribute, self::SETTING_SHOW_FIELD)->getId()) !== false)
                return parent::render();

            return "";
        }

        return parent::render();
    }

    public function wysiwyg($options = [])
    {
        $options = array_merge($this->inputOptions, $options);

        if(isset($this->options['class'])) {
            if(strpos($this->options['class'], 'partial-max-width-item') == false)
                $this->options['class'] .= ' full-width-item';
        } else {
            $this->options['class'] = 'full-width-item';
        }

        $options = array_merge([
            "s3" => "/mata-cms/media/redactor/s3",
            "changeCallback" => new JsExpression('function() {mata.form.hasChanged = true;}')
            ], $options);

        $this->adjustLabelFor($options);
        $this->parts['{input}'] = \mata\imperavi\Widget::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $options,
            'htmlOptions' => [
            'id' => \yii\helpers\Html::getInputId($this->model, $this->attribute)
            ]
            ]);

        return $this;
    }

    public function adjustLabelFor($options)
    {
		parent::adjustLabelFor($options);
    }

    public function dateTime($options = [])
    {
        $options = ArrayHelper::merge([
          'class' => 'form-control',
          ], $options);

		if(isset($this->options['class'])) {
		  if(strpos($this->options['class'], 'full-width-item') == false)
		      $this->options['class'] .= ' partial-max-width-item';
		} else {
		  $this->options['class'] = 'partial-max-width-item';
		}

        $clientOptions = isset($options["clientOptions"]) ? $options["clientOptions"] : [];

        $attribute = $this->attribute;
    
        if (\Yii::$app->user->isGuest) {
            $currentLocalTime = date("Y-m-d H:i:s");
        } else {
            $currentLocalTime = \matacms\helpers\DateHelper::toLocalTime(date('Y-m-d H:i'), \Yii::$app->user->identity->getOffsetFromUTC());
        }

        $minDate = isset($options['minDate']) ? $options['minDate'] : ((!empty($this->model->$attribute) && $this->model->$attribute < $currentLocalTime) ? $this->model->$attribute : $currentLocalTime);

        $clientOptions = ArrayHelper::merge([
            'locale' => 'en',
            'format' => 'YYYY-MM-DD HH:mm',
            'minDate' => $minDate,
            'showTodayButton' => true,
            'icons' => ['today' => 'now-text']
            ], $clientOptions);

        $this->parts['{input}'] = DateTimePicker::widget([
          'model' => $this->model,
          'attribute' => $this->attribute,
          'options' => $options,
          'clientOptions' => $clientOptions
          ]);

        return $this;
    }

    public function selectize($options = [])
    {
        $this->parts['{input}'] = Selectize::widget($options);
        return $this;
    }

    public function media($options = [])
    {
        if(isset($this->options['class'])) {
            $this->options['class'] .= ' partial-max-width-item field-media';
        } else {
            $this->options['class'] = 'partial-max-width-item field-media';
        }

        $this->parts['{input}'] = \mata\widgets\fineuploader\FineUploader::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $options,
            'id' => \yii\helpers\Html::getInputId($this->model, $this->attribute),
            'events' => [
            'complete' => "var inputFileId = '" . \yii\helpers\Html::getInputId($this->model, $this->attribute) . "'; $(this).find('input#" . \yii\helpers\Html::getInputId($this->model, $this->attribute) . "').val(uploadSuccessResponse.DocumentId).trigger('mediaChanged'); mata.form.hasChanged = true;"
            ]
            ]);

        return $this;
    }

    public function autocomplete($items, $options = [])
    {
        if(isset($this->model)) {
            $options['model'] = $this->model;
        }

        if(isset($this->attribute)) {
            $options['attribute'] = $this->attribute;
        }

        $options = ArrayHelper::merge([
            'items' => $items,
            'clientOptions' => ['maxItems' => 1]
            ], $options);

        $this->parts['{input}'] = Selectize::widget($options);
        return $this;
    }

    public function multiselect($items, $options = [])
    {
        $prompt = 'Select ' . Inflector::camel2words($this->attribute);
        if(isset($options['prompt'])) {
            $prompt = $options['prompt'];
            unset($options['prompt']);
        }

		if(isset($this->model)) {
            $options['model'] = $this->model;
        }

        if(isset($this->attribute)) {
            $options['attribute'] = $this->attribute;
        }

        if(isset($this->options['class'])) {
            $this->options['class'] .= ' multi-choice-dropdown partial-max-width-item';
        }

        if(isset($this->model) && isset($this->attribute)) {
            $options['name'] = \matacms\helpers\Html::getInputName($this->model, $this->attribute);
        }

        $options = ArrayHelper::merge([
            'items' => $items,
            'options' => ['multiple' => true, 'prompt' => $prompt],
            'clientOptions' => [],
            ], $options);

        $this->parts['{input}'] = Selectize::widget($options);
        return $this;
    }

    public function slug($fieldName, $options = [])
    {
        if(isset($this->options['class'])) {
            $this->options['class'] .= ' partial-max-width-item';
        } else {
            $this->options['class'] = 'partial-max-width-item';
        }

        $options = ArrayHelper::merge([
            'class' => 'form-control',
            ], $options);

        $this->parts['{input}'] = \matacms\widgets\Slug::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $options,
            'basedOnAttribute' => $fieldName
            ]);

        return $this;
    }

    public function dropDownList($items, $options = [])
    {
        $prompt = 'Select ' . Inflector::camel2words($this->attribute);
        if(isset($options['prompt'])) {
            $prompt = $options['prompt'];
            unset($options['prompt']);
        }

        if(isset($this->model)) {
            $options['model'] = $this->model;
        }

        if(isset($this->attribute)) {
            $options['attribute'] = $this->attribute;
        }

        if(isset($this->options['class'])) {
            $this->options['class'] .= ' single-choice-dropdown partial-max-width-item';
        } else {
            $this->options['class'] = 'single-choice-dropdown partial-max-width-item';
        }

        $options = ArrayHelper::merge([
            'items' => $items,
            'options' => ['multiple'=>false, 'prompt' => $prompt],
            'clientOptions' => [
            'create' => false,
            'persist' => false,
            ]
            ], $options);

        $this->parts['{input}'] = Selectize::widget($options);

        return $this;
    }

    public function hint($content, $options = [])
    {
        if(!empty($content)) {
            $options = array_merge($this->hintOptions, $options);
            $options['data-toggle'] = 'tooltip';
            $options['data-placement'] = 'auto';
            $options['title'] = $content;
            $this->parts['{hint}'] = \yii\helpers\Html::tag('div', '', $options);

            $view = $this->form->getView();
            $js = "$(\"[data-toggle='tooltip']\").tooltip({trigger:'click | hover', html: true, placement: 'bottom'});";
            $view->registerJs($js);
            return $this;
        }
        else {
            $this->parts['{hint}'] = null;
        }

    }

}
