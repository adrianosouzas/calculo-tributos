<?php

class TributoModel extends AbstractModel
{
    public function __construct(array $config = array())
    {
        $this->_data = array('id' => '', 'nome' => '', 'imposto' => '');
        $this->_table = new TributoDbTable();
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

    public function getNome() {
        return $this->nome;
    }

    public function setNome($value) {
        $this->nome = (string) $value;
        return $this;
    }

    public function getImposto() {
        return $this->imposto;
    }

    public function setImposto($value) {
        $this->imposto = (string) $value;
        return $this;
    }
}