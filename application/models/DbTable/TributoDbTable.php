<?php

class TributoDbTable extends AbstractDbTable
{
    protected $_name = 'tributo';
    protected $_rowClass = 'TributoModel';
    protected $_dependentTables = array();
    protected $_referenceMap = array();
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'tributo',
            'COLUMN_NAME' => 'id',
            'COLUMN_POSITION' => 1,
            'DATA_TYPE' => 'int',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => NULL,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => true,
            'PRIMARY' => true,
            'PRIMARY_POSITION' => 1,
            'IDENTITY' => true
        ),
        'nome' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'tributo',
            'COLUMN_NAME' => 'nome',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => 45,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => false,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'imposto' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'tributo',
            'COLUMN_NAME' => 'imposto',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => 10,2,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => false,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        )
    );

    public function findById($value)
    {
        return $this->findBy('id', $value);
    }

    public function findOneById($value)
    {
        return $this->findOneBy('id', $value);
    }

    public function findByNome($value) {
        return $this->findBy('nome', $value);
    }

    public function findOneByNome($value) {
        return $this->findOneBy('nome', $value);
    }

    public function findByImposto($value) {
        return $this->findBy('imposto', $value);
    }

    public function findOneByImposto($value) {
        return $this->findOneBy('imposto', $value);
    }
}