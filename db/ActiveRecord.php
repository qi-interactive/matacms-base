<?php

namespace matacms\db;

use mata\arhistory\behaviors\HistoryBehavior;
use matacms\environment\behaviors\EnvironmentBehavior;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use mata\media\models\Media;
use matacms\interfaces\HumanInterface;

class ActiveRecord extends \mata\db\ActiveRecord implements HumanInterface {

	private $attributeLabels;

    /**
     * This needs to be defined in the base class, otherwise __get will not access the property.
     */ 
    private $_related = [];

    public function behaviors() {
      return [
      HistoryBehavior::className(),
      EnvironmentBehavior::className()
      ];
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

public function setAttributeLabel($attribute, $label)
{
   $attributeLabels = $this->attributeLabels();
   $this->attributeLabels[$attribute] = $label;
}

    /**
     * Returns the text label for the specified attribute.
     * If the attribute looks like `relatedModel.attribute`, then the attribute will be received from the related model.
     * @param string $attribute the attribute name
     * @return string the attribute label
     * @see generateAttributeLabel()
     * @see attributeLabels()
     */
    public function getAttributeLabel($attribute)
    {
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

    public function getInstanceTableSchema()
    {
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

    /**
     *  Returns String;
     */ 
    public function getVisualRepresentation() {

        echo $this->getDocumentId();
        $media = Media::find()
        ->where('DocumentId LIKE :query')
        ->addParams([':query'=>str_replace("\\", "\\\\", $this->getDocumentId()) . '%'])
        ->one();

        if ($media)
            return $media->URI;

        return null;
    }

}