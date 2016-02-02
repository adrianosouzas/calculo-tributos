<?php

class ProdutoForm extends AbstractForm
{
    public function init() {
        $this->setName('produto');

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
            'nome',
            array(
                'label' => 'nome',
                'maxlength' => '45',
                'class' => 'text text-nome required',
                'value' => '',
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        array(
                            0,
                            45
                        )
                    )
                ),
                'required' => true
            )
        );
        $this->addElement(
            'text',
            'chave',
            array(
                'label' => 'chave',
                'maxlength' => '100',
                'class' => 'text text-chave',
                'value' => '',
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        array(
                            0,
                            100
                        )
                    )
                ),
                'required' => false
            )
        );
        $this->addElement(
            'textarea',
            'descricao',
            array(
                'label' => 'Descriçao',
                'cols' => '35',
                'rows' => '10',
                'class' => 'textarea textarea-descricao',
                'value' => '',
                'validators' => array(),
                'required' => false
            )
        );
        $this->addElement(
            'text',
            'preco',
            array(
                'label' => 'Preço',
                'maxlength' => '13',
                'class' => 'text required',
                'value' => '',
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        array(
                            0,
                            13
                        )
                    )
                ),
                'required' => true
            )
        );
        $this->addElement(
            'select',
            'tributo_id',
            array(
                'label' => 'Tributo',
                'class' => 'select select-tributo required',
                'value' => '',
                'validators' => array('int'),
                'required' => true,
                'multiOptions' => $this->getDbOptions('tributo', 'Selecione')
            )
        );
        $this->addElement(
            'select',
            'publicado',
            array(
                'label' => 'Publicado',
                'class' => 'select select-publicado required',
                'value' => '',
                'validators' => array('int'),
                'required' => true,
                'multiOptions' => array(
                    '' => 'Seleciona',
                    0 => 'Não',
                    1 => 'Sim'
                )
            )
        );

        parent::init();
    }

	public function config() {
		$request = $this->getRequest();
		if ($request->getModuleName() == 'admin'){
			$this->getElement('preco')
                ->setAttrib('class', $this->getElement('preco')->getAttrib('class') . ' decimal');
            $this->removeFromIteration('chave');
		}
	}
}
