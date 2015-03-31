<?php 

namespace matacms\widgets;

use Yii;
use yii\helpers\Json;
use yii\base\Event;
use mata\base\MessageEvent;
use yii\selectize\Selectize;
use zhuravljov\widgets\DatePicker;
use zhuravljov\widgets\DateTimePicker;
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\widgets\ActiveField {

	public $model;

    const EVENT_INIT_DONE = "matacms\widgets\ActiveField::EVENT_INIT_DONE";

    public function init()
    {
        Event::trigger(self::className(), self::EVENT_INIT_DONE, new MessageEvent($this));
    }

    public function wysiwyg($options = [])
    {
        $options = array_merge($this->inputOptions, $options);

        $options = array_merge([
            "s3" => "/mata-cms/media/redactor/s3",
        ], $options);

        $this->adjustLabelFor($options);
        $this->parts['{input}'] = \yii\imperavi\Widget::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $options
        ]);

        return $this;
    }

    public function adjustLabelFor($options) 
    {
        if (isset($options['id']) && !isset($this->labelOptions['for'])) {
            $this->labelOptions['for'] = $options['id'];
        }
    }

    public function dateTime($options = [])
    {

        $options = ArrayHelper::merge([
          'class' => 'form-control',
          ], $options);

        $clientOptions = isset($options["clientOptions"]) ? $options["clientOptions"] : [];

        $clientOptions = ArrayHelper::merge([
          'autoclose' => true,
            // 'format' => 'd MM yyyy hh:ii',
          'todayHighlight' => true,
          'weekStart' => 1
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
        $this->parts['{input}'] = \mata\widgets\fineuploader\FineUploader::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $options,
            'events' => [
                'complete' => "$(this).closest('form').append('<input type=\"hidden\" name=\"Media[]\" value=\"' + uploadSuccessResponse.DocumentId + '\">');"
            ]
        ]);

        return $this;
    }

    public function autocomplete($items, $options = [])
    {
        $options = ArrayHelper::merge([
            'items' => $items,
            'clientOptions' => ['maxItems' => 1]
        ], $options);

        $this->parts['{input}'] = Selectize::widget($options);
            return $this;
    }

    public function multiselect($items, $options = [])
    {
        $options = ArrayHelper::merge([
            'items' => $items,
            'options' => ['multiple' => true],
            'clientOptions' => []
        ], $options);

        $this->parts['{input}'] = Selectize::widget($options);
        return $this;
    }

    public function slug($fieldName, $options = []) 
    {
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
        $prompt = 'Choose ...';
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
}
