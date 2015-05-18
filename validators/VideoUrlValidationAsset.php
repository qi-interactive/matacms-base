<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\validators;

use yii\web\AssetBundle;

class VideoUrlValidationAsset extends AssetBundle
{
    public $sourcePath = '@vendor/matacms/matacms-base/web';

    public $js = [
        'js/matacms.validation.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
