<?php

namespace matacms\base;

interface CalendarInterface
{
    
    public static function getEventDateAttribute();

    public function getEventDate();
}