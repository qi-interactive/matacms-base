<?php 

namespace matacms\base;

class MessageEvent extends \mata\base\MessageEvent {

    const LEVEL_SUCCESS = "success";
    const LEVEL_INFO = "info";
    const LEVEL_WARNING = "danger";
    const LEVEL_ERROR = "danger";

    private $level;

    public function __construct($message, $level = self::LEVEL_SUCCESS) {
        $this->level = $level;
        parent::__construct($message);
    }

    public function getLevel() {
        return $this->level;
    }
}