<?php

class LoginForm extends UsuarioForm {
	
	public function config() {
		parent::config();
		
		$this->setName('login');
		
		$this->senha = new Izi_Form_Element_Password(
            'senha',
            array(
                'label' => 'senha',
                'class' => 'text',
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        array(
                            0,
                            32
                        )
                    )
                ),
                'required' => true
            )
        );
		$this->addElement(
            'hidden',
            'referer',
            array(
                'label' => 'referer',
                'validators' => array(
                    new Izi_Validate_Uri()
                ),
                'required' => false
            )
        );
		$this->removeElement('nome');
		$this->getElement('submit')->setLabel('entrar');
	}
}
