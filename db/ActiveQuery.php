<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\db;

use Yii;
use matacms\base\MessageEvent;

class ActiveQuery extends \mata\db\ActiveQuery {

	/**
	 * Prepares for building SQL.
	 * This event is fired by [[QueryBuilder]] when it starts to build SQL from a query object.
	 * You may override this method to do some final preparation work when converting a query into a SQL statement.
	 * @return MessageEvent, with [[$this]] as sender and [[$builder]] as message.
	 */
	const EVENT_BEFORE_PREPARE_STATEMENT = "EVENT_BEFORE_PREPARE_STATEMENT";

	public function prepare($builder) {
		$this->trigger(self::EVENT_BEFORE_PREPARE_STATEMENT);
	    return parent::prepare($builder);
	}

	/**
	 * Changed visibility to public 
	 * @param ActiveQuery $query
	 * @return array the table name and the table alias.
	 */
	public function getQueryTableName($query) {
	    if (empty($query->from)) {
	        /* @var $modelClass ActiveRecord */
	        $modelClass = $query->modelClass;
	        $tableName = $modelClass::tableName();
	    } else {
	        $tableName = '';
	        foreach ($query->from as $alias => $tableName) {
	            if (is_string($alias)) {
	                return [$tableName, $alias];
	            } else {
	                break;
	            }
	        }
	    }

	    if (preg_match('/^(.*?)\s+({{\w+}}|\w+)$/', $tableName, $matches)) {
	        $alias = $matches[2];
	    } else {
	        $alias = $tableName;
	    }

	    return [$tableName, $alias];
	}
}
