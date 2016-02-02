<?php

class ProdutoModel extends AbstractModel
{
    public function __construct(array $config = array())
    {
        $this->_data = array(
            'id' => '',
            'nome' => '',
            'chave' => '',
            'descricao' => '',
            'preco' => '',
            'publicado' => '',
            'tributo_id' => ''
        );
        $this->_table = new ProdutoDbTable();
        parent::__construct($config);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = (int) $value;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($value)
    {
        $this->nome = (string) $value;
        return $this;
    }

    public function getChave()
    {
        return $this->chave;
    }

    public function setChave($value)
    {
        $this->chave = (string) $value;
        return $this;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($value)
    {
        $this->descricao = (string) $value;
        return $this;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($value)
    {
        $this->preco = (string) $value;
        return $this;
    }

    public function getPublicado()
    {
        return $this->publicado;
    }

    public function setPublicado($value)
    {
        $this->publicado = (string) $value;
        return $this;
    }

    public function getTributoId()
    {
        return $this->tributoId;
    }

    public function setTributoId($value)
    {
        $this->tributoId = (string) $value;
        return $this;
    }
}