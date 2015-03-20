<?php

namespace matacms\widgets;

use Yii;
use yii\helpers\Html;

class Slug extends \yii\widgets\InputWidget
{
	public $options = [];
	
	public $basedOnAttribute;

	public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
    }

    public function run()
    {
        echo Html::beginTag('div');
        echo Html::button('Generate URI', ['id'=>'generate-uri', 'style'=>'float:right;', 'class'=>'btn btn-info']);
        echo Html::beginTag('div', ['style'=>'overflow: hidden; padding-right: .5em;']);
        if (!empty($this->model)) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }
        echo Html::endTag('div');
        echo Html::endTag('div');

        $this->registerJS();
    }

    protected function registerJS()
    {
        $basedId = Html::getInputId($this->model, $this->basedOnAttribute);
        $id = Html::getInputId($this->model, $this->attribute);
        $view = $this->getView();
        $view->registerJs("$('#generate-uri').on('click', function() { $('#$id').val($.trim($('#$basedId').val()).toLowerCase().replace(/[^a-z0-9-]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g, '').toLowerCase());});");
    }
	
}