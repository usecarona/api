<?php

/**
 * CCompositeUniqueValidator class file.
 *
 * @package extensions
 * @subpackage composite-validators
 * @filesource
 */

/**
 * CCompositeUniqueValidator validates that the composite attribute value is unique in the corresponding database table.
 *
 * @author Juaum <joao.paulo@cotacoesecompras.com.br>
 * @package extensions
 * @subpackage composite-validators
 */
class CCompositeUniqueValidator extends CValidator
{

    /**
     * @var boolean whether the comparison is case sensitive. Defaults to true.
     * Note, by setting it to false, you are assuming the attribute type is string.
     */
    public $caseSensitive = true;

    /**
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty = true;

    /**
     * @var string the ActiveRecord class name that should be used to
     * look for the attribute value being validated. Defaults to null, meaning using
     * the class of the object currently being validated.
     * You may use path alias to reference a class name here.
     */
    public $className;

    /**
     * @var string the ActiveRecord class attribute name that should be
     * used to look for the attribute value being validated. Defaults to null,
     * meaning using the name of the attribute being validated.
     */
    public $attributeName;

    /**
     * @var array additional query criteria. This will be combined with the condition
     * that checks if the attribute value exists in the corresponding table column.
     * This array will be used to instantiate a {@link CDbCriteria} object.
     */
    public $criteria = array();

    /**
     * @var string the user-defined error message. The placeholders "{attribute}" and "{value}"
     * are recognized, which will be replaced with the actual attribute name and value, respectively.
     */
    public $message;

    /**
     * @var boolean whether this validation rule should be skipped if when there is already a validation
     * error for the current attribute. Defaults to true.
     */
    public $skipOnError = true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        $attributes = explode('|', $attribute);
        $values = array();
        foreach ($attributes as $attr) {
            $values[] = $object->$attr;
        }

        $allEmpty = true;
        foreach ($values as $value) {
            if (!($this->allowEmpty && $this->isEmpty($value))) {
                $allEmpty = false;
                break;
            }
        }
        if ($allEmpty) {
            return;
        }

        $className = $this->className === null ? get_class($object) : Yii::import($this->className);
        $attributesName = $this->attributeName === null ? $attributes : explode('|', $this->attributeName);
        $finder = CActiveRecord::model($className);
        $table = $finder->getTableSchema();

        $columns = array();
        foreach ($attributesName as $attributeName) {
            if (($column = $table->getColumn($attributeName)) === null) {
                throw new CException(Yii::t('yii', 'Table "{table}" does not have a column named "{column}".', array('{column}' => $attributeName, '{table}' => $table->name)));
            }
            $columns[] = $column;
        }

        $conditions = array();
        $params = array();
        foreach ($columns as $index => $column) {
            $conditions[] = $this->caseSensitive ? "{$column->name}=:{$column->name}" : "LOWER({$column->name})=LOWER(:{$column->name})";
            $params[":{$column->name}"] = $values[$index];
        }

        $condition = implode(' AND ', $conditions);
        $criteria = array('condition' => $condition, 'params' => $params);
        $criteria = new CDbCriteria($criteria);

        if ($this->criteria !== array()) {
            $criteria->mergeWith($this->criteria);
        }

        if (!$object instanceof CActiveRecord || $object->isNewRecord || $object->tableName() !== $finder->tableName())
            $exists = $finder->exists($criteria);
        else {
            $criteria->limit = 2;
            $objects = $finder->findAll($criteria);
            $n = count($objects);
            if ($n === 1) {
                if ($column->isPrimaryKey)  // primary key is modified and not unique
                    $exists = $object->getOldPrimaryKey() != $object->getPrimaryKey();
                else // non-primary key, need to exclude the current record based on PK
                    $exists = $objects[0]->getPrimaryKey() != $object->getOldPrimaryKey();
            }
            else
                $exists = $n > 1;
        }

        if ($exists) {
            $message = $this->message !== null ? $this->message : Yii::t('app', 'The composite key {attribute} "{value}" has already been taken.');
            $this->addError($object, str_replace('|', ',', $attribute), $message, array('{value}' => implode(',', $values)));
        }
    }

}

