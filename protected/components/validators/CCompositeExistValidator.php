<?php

/**
 * CCompositeExistValidator class file.
 *
 * @package extensions
 * @subpackage composite-validators
 * @filesource
 */

/**
 * CCompositeExistValidator validates that the composite attribute value exists in a table.
 *
 * This validator is often used to verify that a composite foreign key contains a value
 * that can be found in the foreign table.
 *
 * @author Juaum <joao.paulo@cotacoesecompras.com.br>
 * @package extensions
 * @subpackage composite-validators
 */
class CCompositeExistValidator extends CValidator
{

    /**
     * @var string the ActiveRecord class name that should be used to
     * look for the attribute value being validated. Defaults to null,
     * meaning using the ActiveRecord class of the attribute being validated.
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
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty = true;

    /**
     * @var boolean whether have to be validate all attributes. Defaults to true,
     * meaning that is empty just when all the attributes are empty.
     */
    public $verifyAllFields = true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attributes being validated separated by pipe
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
                if ($this->verifyAllFields) {
                    break;
                }
            } else if (!$this->verifyAllFields) {
                return;
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
            $conditions[] = $column->name . '=:' . $column->name;
            $params[":{$column->name}"] = $values[$index];
        }

        $condition = implode(' AND ', $conditions);
        $criteria = array('condition' => $condition, 'params' => $params);
        if ($this->criteria !== array()) {
            $criteria = new CDbCriteria($criteria);
            $criteria->mergeWith($this->criteria);
        }

        if (!$finder->exists($criteria)) {
            $message = $this->message !== null ? $this->message : Yii::t('app', 'The composite key {attribute} "{value}" is invalid.');
            $this->addError($object, str_replace('|', ', ', $attribute), $message, array('{value}' => implode(', ', $values)));
        }
    }

}

