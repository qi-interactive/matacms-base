<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\widgets\videourl\helpers;

use yii\helpers\Json;
use yii\helpers\Html;

class VideoUrlHelper 
{

	public static function getVideoServiceProvider($videoUrl) 
    {
        $url = preg_replace('#\#.*$#', '', trim($videoUrl));
        $services_regexp = [
        '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/'     => 'vimeo',
        '/(?:https?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([\w-]{10,})/'     => 'youtube'
        ];

        foreach ($services_regexp as $pattern => $service) {
            if(preg_match($pattern, $videoUrl, $matches)) {
                return $service;
            }
        }

        return false;
    }

    public static function getVideoId($videoUrl) 
    {
        $url = preg_replace('#\#.*$#', '', trim($videoUrl));
        $services_regexp = [
        '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*(?P<id>[0-9]{6,11})[?]?.*/'     => 'vimeo',
        '/(?:https?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?(?P<id>[\w-]{10,})/'     => 'youtube'
        ];

        foreach ($services_regexp as $pattern => $service) {
            if(preg_match($pattern, $videoUrl, $matches)) {
                return $matches['id'];
            }
        }

        return false;
    }

    public static function renderVideoPlayer($videoUrl, $options = []) 
    {
        $videoProvider = self::getVideoServiceProvider($videoUrl);
        $videoId = self::getVideoId($videoUrl);

        $videoPlayerCode = '';

        $playerId = isset($options["playerId"]) ? $options["playerId"] : "video-player";

        $id = isset($options["id"]) ? $options["id"] : $playerId;

        $tagAttributes = Html::renderTagAttributes($options);

        switch($videoProvider) {
        	case 'vimeo':
                // TODO get in one call
                $width = self::vimeoApi($videoId, "width");
                $height = self::vimeoApi($videoId, "height");
        		$videoPlayerCode = '<iframe id="' . $id . '" ' . $tagAttributes . ' src="//player.vimeo.com/video/' . $videoId . '?autoplay=0&api=1&player_id=' . $playerId . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        		break;
        	default:
        		$videoPlayerCode = '<iframe width="500" height="281" src="http://www.youtube.com/embed/' . $videoId . '" ' . $tagAttributes . '></iframe>';
        		break;
        }
        return $videoPlayerCode;
    }

    public static function renderHTML5VideoPlayer($sources = [], $options = []) {

        if (!is_array($sources))
            $sources = [$sources];


        $sourcesHtml = "";

        foreach ($sources as $source)
            $sourcesHtml .= Html::tag('source', '', [
                'src' => $source
                ]);


        return Html::tag("video", $sourcesHtml, $options);
    }

    public static function getPicture($videoUrl) 
    {
        $videoProvider = self::getVideoServiceProvider($videoUrl);
        $videoId = self::getVideoId($videoUrl);

        $videoImage = false;

        switch($videoProvider) {
            case 'vimeo':
                $videoImage = self::vimeoApi($videoId, "thumbnail_medium");
                break;
            case 'youtube':
                $videoImage = self::youtubeApi($videoId);
                // $videoImage = '<img src="' . $videoId . '">';
                break;
            default:
                break;
        }
        return $videoImage;
    }

    public static function vimeoApi($videoId, $property) 
    {
        $contents = @file_get_contents('https://vimeo.com/api/v2/video/' . $videoId . '.json');
        if(!$contents)
            return false;
        $contents = Json::decode($contents, false);

        return $contents[0]->$property;
    }

    public static function youtubeApi($videoId) 
    {
        return 'https://img.youtube.com/vi/' . $videoId . '/0.jpg';
    }
    
}
