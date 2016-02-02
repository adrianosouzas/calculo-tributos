<?php

class UsuarioModel extends AbstractModel
{
    public function __construct(array $config = array())
    {
        $this->_data = array('id' => '', 'email' => '', 'senha' => '');
        $this->_table = new UsuarioDbTable();
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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = (string) $value;
        return $this;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($value)
    {
        $this->senha = (string) $value;
        return $this;
    }
}