<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\interfaces;

interface CalendarInterface
{
    public static function getEventDateAttribute();

    public function getEventDate();
}
