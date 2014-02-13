<?php

/**
 * Arquivo da classe PHPExcel_Cell_StringValueBinder.
 *
 * @package api\extensions\PHPExcel\Cell\
 * @filesource
 */

/**
 * Classe que seta como string todos os valores do arquivo que será carregado.
 * @author Gustavo Sávio <gustavo.savio@cotacoesecompras.com.br>
 */
class PHPExcel_Cell_StringValueBinder extends PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{
    /*
     * Efetua a conversão.
     */

    public function bindValue(PHPExcel_Cell $cell, $value = null)
    {
        if ($value) {
            $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

}

