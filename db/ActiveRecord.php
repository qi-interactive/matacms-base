<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\db;

use mata\arhistory\behaviors\HistoryBehavior;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use mata\media\models\Media;
use matacms\interfaces\HumanInterface;
use Yii;

class ActiveRecord extends \mata\db\ActiveRecord implements HumanInterface {

	private $attributeLabels;

    /**
     * This needs to be defined in the base class, otherwise __get will not access the property.
     */
    private $_related = [];

    public function behaviors() {
        return [
            HistoryBehavior::className()
        ];
    }

	public function __get($name) {
	    if (!is_a(\Yii::$app, "yii\console\Application") && !\Yii::$app->user->isGuest && \yii\helpers\StringHelper::startsWith($name, 'Local'))
			return \matacms\helpers\DateHelper::toLocalTime($this->getAttribute(\yii\helpers\StringHelper::byteSubstr($name, strlen('Local'))), \Yii::$app->user->identity->getOffsetFromUTC());

		return parent::__get($name);
	}

	public function __set($name, $value)
    {
		if (!is_a(\Yii::$app, "yii\console\Application") && !\Yii::$app->user->isGuest && \yii\helpers\StringHelper::startsWith($name, 'Local')) {
			$originName = \yii\helpers\StringHelper::byteSubstr($name, strlen('Local'));

			if ($this->hasAttribute($originName)) {
	            $this->setAttribute($originName, \matacms\helpers\DateHelper::toUTCTime($value, \Yii::$app->user->identity->getOffsetFromUTC()));
	        }
			else {
				parent::__set($name, $value);
			}
		}
		else {
			parent::__set($name, $value);
		}
    }

    public static function find() {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    public function getLabel() {

        if ($this->hasAttribute("Name") && !empty($this->Name))
            return $this->Name;

        if ($this->hasAttribute("Title") && !empty($this->Title))
            return $this->Title;

        return $this->getPrimaryKey();
    }

    public function getModelLabel() {
        $reflection = new \ReflectionClass($this);
        return Inflector::camel2words($reflection->getShortName());
    }

    public function getTableName() {
        return static::tableName();
    }

    // WHAT IS THIS FUNCTION? How is it different from attributeLabels()?
    public function getAttributeLabels($attribute = null) {
        if($this->attributeLabels == null)
            $this->attributeLabels = $this->attributeLabels();
        return $this->attributeLabels;
    }

    public function setAttributeLabel($attribute, $label) {
        $attributeLabels = $this->attributeLabels();
        $this->attributeLabels[$attribute] = $label;
    }

    public function getAttributeLabel($attribute) {
        $labels = $this->getAttributeLabels();
        if (isset($labels[$attribute])) {
            return ($labels[$attribute]);
        } elseif (strpos($attribute, '.')) {
            $attributeParts = explode('.', $attribute);
            $neededAttribute = array_pop($attributeParts);

            $relatedModel = $this;
            foreach ($attributeParts as $relationName) {
                if (isset($this->_related[$relationName]) && $this->_related[$relationName] instanceof self) {
                    $relatedModel = $this->_related[$relationName];
                } else {
                    try {
                        $relation = $relatedModel->getRelation($relationName);
                    } catch (InvalidParamException $e) {
                        return $this->generateAttributeLabel($attribute);
                    }
                    $relatedModel = new $relation->modelClass;
                }
            }

            $labels = $relatedModel->attributeLabels();
            if (isset($labels[$neededAttribute])) {
                return $labels[$neededAttribute];
            }
        }

        return $this->generateAttributeLabel($attribute);
    }

    public function getInstanceTableSchema() {
        $schema = static::getDb()->getSchema()->getTableSchema($this->getTableName());
        if ($schema !== null) {
            return $schema;
        } else {
            throw new InvalidConfigException("The table does not exist: " . $this->getTableName());
        }
    }

    public function filterableAttributes() {
        return [];
    }

    public function getVisualRepresentation() {

        $query = str_replace("\\", "\\\\", $this->getDocumentId()) . '%';
        $media= \Yii::$app->cache->get(__CLASS__ . md5($query));

        if($media===false)
        {
            $media = Media::find()
                ->where('`For` LIKE :query')
                ->addParams([':query'=> $query])
                ->one();

            \Yii::$app->cache->set(__CLASS__ . md5($query),$media, null, new \matacms\cache\caching\MataLastUpdatedTimestampDependency());
        }

        if ($media)
            return $media->URI;

        return null;
    }

    public function canBeDeleted() {
        return true;
    }

    public function deleteAlertMessage() {
        return;
    }
}
