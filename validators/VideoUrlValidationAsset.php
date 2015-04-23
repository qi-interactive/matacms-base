<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace matacms\validators;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for client validation.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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
