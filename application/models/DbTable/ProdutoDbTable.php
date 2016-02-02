<?php

class ProdutoDbTable extends AbstractDbTable
{
    protected $_name = 'produto';
    protected $_rowClass = 'ProdutoModel';
    protected $_dependentTables = array();
    protected $_referenceMap = array();
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'produto',
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
            'TABLE_NAME' => 'produto',
            'COLUMN_NAME' => 'nome',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => 100,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => false,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'chave' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'produto',
            'COLUMN_NAME' => 'chave',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => 100,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => false,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'descricao' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'produto',
            'COLUMN_NAME' => 'descricao',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'text',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => NULL,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => NULL,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'preco' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'produto',
            'COLUMN_NAME' => 'preco',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => 13,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => false,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'publicado' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'pagina',
            'COLUMN_NAME' => 'publicado',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => NULL,
            'SCALE' => NULL,
            'PRECISION' => NULL,
            'UNSIGNED' => NULL,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => NULL,
            'IDENTITY' => false
        ),
        'tributo_id' => array(
            'SCHEMA_NAME' => NULL,
            'TABLE_NAME' => 'pagina',
            'COLUMN_NAME' => 'tributo_id',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'int',
            'DEFAULT' => NULL,
            'NULLABLE' => false,
            'LENGTH' => NULL,
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

    public function findByNome($value)
    {
        return $this->findBy('nome', $value);
    }

    public function findOneByNome($value)
    {
        return $this->findOneBy('nome', $value);
    }

    public function findByChave($value)
    {
        return $this->findBy('chave', $value);
    }

    public function findOneByChave($value)
    {
        return $this->findOneBy('chave', $value);
    }

    public function findByPreco($value)
    {
        return $this->findBy('preco', $value);
    }

    public function findOneByPreco($value)
    {
        return $this->findOneBy('preco', $value);
    }

    public function findByTributoId($value)
    {
        return $this->findBy('tributo_id', $value);
    }

    public function findOneByTributoId($value)
    {
        return $this->findOneBy('tributo_id', $value);
    }

    public function produtoLista() {
        $select = $this->select()
            ->from('produto AS p', '*')
            ->joinInner('tributo AS t', 'p.tributo_id = t.id', array('imposto'))
            ->where('p.publicado = ?', 1)
            ->order('p.nome ASC')
            ->setIntegrityCheck(false);

        return $this->fetchAll($select);
    }

    public function produtoById($id) {
        $select = $this->select()
            ->from('produto AS p', '*')
            ->joinInner('tributo AS t', 'p.tributo_id = t.id', array('imposto'))
            ->where('p.id = ?', $id)
            ->setIntegrityCheck(false);

        return $this->fetchRow($select);
    }
}