<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\widgets\videourl;

use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\web\View;
use yii\base\InvalidConfigException;
use matacms\widgets\videourl\VideoUrlAsset;
use mata\keyvalue\models\KeyValue;
use mata\media\models\Media;
use matacms\widgets\videourl\models\VideoUrlForm;

class VideoUrl extends InputWidget {

    public $endpoint;
    public $view = 'videourl';

    public $selector = null;
    public $htmlOptions = [];
    public $options = [
        'showSubmitButton' => true
    ];
    public $formModel;
    public $onComplete = "$('<li role=\"option\" aria-grabbed=\"false\" draggable=\"true\"><a href=\"#\" class=\"edit-media\" data-title=\"Edit Media\" data-url=\"/mata-cms/carousel/carousel-item/update?id='+data.Id+'\" data-source=\"\" data-toggle=\"modal\" data-target=\"#media-modal\"><span class=\"glyphicon glyphicon-pencil\"></span></a><a href=\"#\" class=\"delete-media\" data-url=\"/mata-cms/carousel/carousel-item/delete?id='+data.Id+'\"><span class=\"glyphicon glyphicon-remove\"></span></a><div class=\"grid-item\" data-item-id=\"'+data.Id+'\"><div class=\"grid-item-centerer\"></div><img src=\"' + data.Extra.thumbnailUrl + '\" draggable=\"false\"></div></li>').insertBefore('.carousel-view ul.sortable li#add-media-container');
                    $('ul.sortable').matasortable('reload');
                    $('#media-modal').modal('hide');";

    public function init(){
        parent::init();

        if (empty($this->endpoint))
            throw new InvalidConfigException("endpoint property must be specified.");

        if(empty($this->formModel))
            $this->formModel = new VideoUrlForm;

        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }

    }

    public function run()
    {
        $this->selector = '#' . $this->htmlOptions['id'];

        $this->registerPlugin();
        $this->registerJS();

        echo $this->render($this->view, [
            "widget" => $this,
            "formModel" => $this->formModel
            ]);
    }

    /**
     * Registers plugin and the related events
     */
    protected function registerPlugin()
    {
        $view = $this->getView();
        VideoUrlAsset::register($view);
    }

    /**
     * Register JS
     */
    protected function registerJS() {
        $options = Json::encode($this->options);
        
    }
}
