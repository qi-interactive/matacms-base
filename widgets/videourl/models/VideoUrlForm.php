<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\widgets\videourl\models;

use Yii;
use yii\base\Model;

class VideoUrlForm extends Model
{
    public $videoUrl;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['videoUrl'], 'required'],
            // email has to be a valid email address
            ['videoUrl', '\matacms\validators\VideoUrlValidator'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'videoUrl' => 'Video URL',
        ];
    }

}
