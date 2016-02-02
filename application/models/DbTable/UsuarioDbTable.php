<?php

class UsuarioDbTable extends AbstractDbTable
{
    protected $_name = 'usuario';
    protected $_rowClass = 'UsuarioModel';
    protected $_dependentTables = array();
    protected $_referenceMap = array();
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'usuario',
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
        'email' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'usuario',
            'COLUMN_NAME' => 'email',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => '200',
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => NULL,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'senha' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'usuario',
            'COLUMN_NAME' => 'senha',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'char',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => '32',
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => NULL,
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

    public function findByEmail($value)
    {
        return $this->findBy('email', $value);
    }

    public function findOneByEmail($value)
    {
        return $this->findOneBy('email', $value);
    }

    public function findBySenha($value)
    {
        return $this->findBy('senha', $value);
    }

    public function findOneBySenha($value)
    {
        return $this->findOneBy('senha', $value);
    }
}