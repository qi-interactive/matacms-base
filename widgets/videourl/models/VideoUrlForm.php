<?php

namespace matacms\widgets\videourl\models;

use Yii;
use yii\base\Model;

/**
 * VideoUrlForm is the model behind the video url form.v
 */
class VideoUrlForm extends Model
{
    public $videoUrl;
    // 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['videoUrl'], 'required'],
            // email has to be a valid email address
            ['videoUrl', '\matacms\validators\VideoUrlValidator'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'videoUrl' => 'Video URL',
        ];
    }

}
