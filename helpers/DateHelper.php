<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\helpers;
use yii\db\Schema;

class DateHelper
{

    public static function toLocalTime($dateTime, $offset = 0)
    {
        return date("Y-m-d H:i:s", strtotime($dateTime . " " . $offset . " hours"));
    }

    public static function toUTCTime($dateTime, $offset = 0)
    {
        $offset = strrpos($offset, "+") > -1 ? str_replace("+", "-", $offset) : str_replace("-", "+", $offset);
        return date("Y-m-d H:i:s", strtotime($dateTime . " " . $offset . " hours"));
    }

    public static function isDateType($model, $attribute)
    {
        $attributeDbType = $model->getTableSchema()->getColumn($attribute)->dbType;
        return $attributeDbType == Schema::TYPE_DATE || $attributeDbType == Schema::TYPE_TIME || $attributeDbType == Schema::TYPE_DATETIME || $attributeDbType == Schema::TYPE_TIMESTAMP;
    }

}
