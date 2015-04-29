<?php

namespace matacms\widgets\InfiniteScrollPager;

// use Yii;
// use yii\base\InvalidConfigException;
// use yii\base\Widget;
// use yii\helpers\ArrayHelper;
// use yii\helpers\Html;
use yii\helpers\Json;
// use yii\helpers\Url;
// use matacms\widgets\InfiniteScrollListPager\InfiniteScrollListPagerAsset;
use yii\widgets\Pjax;

class InfiniteScrollPager extends \yii\widgets\LinkPager
{

    public $clientOptions;
    
    public function init()
    {
        parent::init();
        $this->options['class'] = 'pagination hidden';
    }

    public function run()
    {

    
        $clientOptions = [
            'pjax' => [
                'id' => $this->clientOptions['pjax']['id']
            ],
            'id' => $this->clientOptions['listViewId']
        ];

        $clientOptions = Json::encode($this->clientOptions);

        $view = $this->getView();
        InfiniteScrollPagerAsset::register($view);
        $view->registerJs("matacms.infinitePager.init($clientOptions);");

        parent::run();        
    }


}