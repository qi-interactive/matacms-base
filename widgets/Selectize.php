<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

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
     */
    public $clientOptions;
    /**
     * @var array
     */
    public $clientEvents;

    public function init()
    {
        parent::init();
    }

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
