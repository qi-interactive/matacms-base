<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\widgets\videourl;

use yii\web\AssetBundle;

class VideoUrlAsset extends AssetBundle {

    public $sourcePath = '@vendor/matacms/matacms-base/widgets/videourl/assets';
    
    public $js = [
        'js/videourl.js'
    ];
    
    public $css = [
        'css/videourl.css',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
