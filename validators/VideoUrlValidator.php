<?php

namespace matacms\validators;

use Yii;
use yii\validators\Validator;
use yii\web\JsExpression;
use yii\helpers\Json;
use matacms\validators\VideoUrlValidationAsset;

class VideoUrlValidator extends Validator
{
    public $vimeoPattern = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/';
    public $youtubePattern = '/(?:https?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([\w-]{10,})/';

    public function init()
    {
        parent::init();
        if ($this->message === null)
            $this->message = Yii::t('yii', '{attribute} is not a valid video url.');
        
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if(!$this->identifyVideoServiceProvider($value))
            $model->addError($attribute, \Yii::t('yii', '{attribute} is not a valid video url.', ['attribute' => $model->getAttributeLabel($attribute)]));

    }

    public function clientValidateAttribute($model, $attribute, $view) {

        $options = [
            'vimeoPattern' => new JsExpression($this->prepareJsPattern($this->vimeoPattern)),
            'youtubePattern' => new JsExpression($this->prepareJsPattern($this->youtubePattern)),
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ], Yii::$app->language),
        ];

        VideoUrlValidationAsset::register($view);
        return 'matacms.validation.videourl(value, messages, ' . Json::encode($options) . ');';
    }

    protected function identifyVideoServiceProvider($attribute) {
        $url = preg_replace('#\#.*$#', '', trim($attribute));
        $services_regexp = [
            $this->vimeoPattern       => 'vimeo',
            $this->youtubePattern     => 'youtube'
        ];

        foreach ($services_regexp as $pattern => $service) {
            if(preg_match($pattern, $attribute, $matches)) {
                return $service;
            }
        }

        return false;
    }

    protected function prepareJsPattern($pattern) {
        $pattern = preg_replace('/\\\\x\{?([0-9a-fA-F]+)\}?/', '\u$1', $pattern);
        $deliminator = substr($pattern, 0, 1);
        $pos = strrpos($pattern, $deliminator, 1);
        $flag = substr($pattern, $pos + 1);
        if ($deliminator !== '/') {
            $pattern = '/' . str_replace('/', '\\/', substr($pattern, 1, $pos - 1)) . '/';
        } else {
            $pattern = substr($pattern, 0, $pos + 1);
        }
        if (!empty($flag)) {
            $pattern .= preg_replace('/[^igm]/', '', $flag);
        }
        return $pattern;
    }
}