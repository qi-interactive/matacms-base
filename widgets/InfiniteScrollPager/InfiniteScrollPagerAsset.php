<?php

namespace matacms\widgets\InfiniteScrollPager;

use yii\web\AssetBundle;

class InfiniteScrollPagerAsset extends AssetBundle
{
	public $sourcePath = '@vendor/matacms/matacms-base/widgets/InfiniteScrollPager/assets';

	public $css = [
		'css/infinitepager.css'
	];
    
	public $js = [
		'js/infinitepager.js'
	];
}
