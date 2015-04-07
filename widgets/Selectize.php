<?php
namespace matacms\widgets;

use matacms\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\jui\JuiAsset;

class Selectize extends \yii\selectize\Selectize
{
    /**
     * @var array
     */
    public $items;
    /**
     * @var array
     * @see https://github.com/brianreavis/selectize.js/blob/master/docs/usage.md#options
     */
    public $clientOptions;
    /**
     * @var array
     * @see https://github.com/brianreavis/selectize.js/blob/master/docs/events.md
     */
    public $clientEvents;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            if (is_array($this->items)) {
                echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
            } else {
                echo Html::activeTextInput($this->model, $this->attribute, $this->options);
            }
        } else {
            if (is_array($this->items)) {
                echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
            } else {
                echo Html::textInput($this->name, $this->value, $this->options);
            }
        }
    }
} 