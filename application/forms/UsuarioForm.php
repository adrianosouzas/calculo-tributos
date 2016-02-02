<?php

class UsuarioForm extends AbstractForm
{
    public function init() {
        $this->setName('usuario');

        $this->addElement(
            'hidden',
            'id',
            array(
                'label' => 'id',
                'value' => '',
                'validators' => array(),
                'required' => false
            )
        );
        $this->addElement(
            'text',
            'email',
            array(
                'label' => 'email',
                'maxlength' => '100',
                'class' => 'text text-email required',
                'value' => '',
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        array(
                            0,
                            200
                        )
                    )
                ),
                'required' => true
            )
        );
        $this->addElement(
            'text',
            'senha',
            array(
                'label' => 'senha',
                'maxlength' => '32',
                'class' => 'text text-senha required',
                'value' => '',
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

        parent::init();
    }
	
	public function config() {
		$request = $this->getRequest();

		if ($request->getModuleName() == 'admin'
            && ($request->getActionName() == 'new' || $request->getActionName() == 'save'))
        {
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
			$this->confirmasenha = new Izi_Form_Element_Password(
                'confirmasenha',
                array(
                    'label' => 'Confirma Senha',
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
                    'required' => false
                )
            );
		}
		
		if ($request->getModuleName() == 'admin' && $request->getActionName() == 'edit') {
			$this->removeElement('senha');
		}
		
		if ($request->getModuleName() == 'admin' && in_array($request->getActionName(), array('index', 'find'))) {
			$this->removeElement('senha');
		}
	}
	
}