<?php
/** 
 * @author: Harry Tang (giaduy@gmail.com)
 * @link: http://www.greyneuron.com 
 * @copyright: Grey Neuron
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