<?php

class TributoForm extends AbstractForm
{
    public function init() {
        $this->setName('tributo');

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
            'imposto',
            array(
                'label' => 'imposto',
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

        parent::init();
    }

	public function config() {
		$request = $this->getRequest();
		if ($request->getModuleName() == 'admin'){
			$this->getElement('imposto')
                ->setAttrib('class', $this->getElement('imposto')->getAttrib('class') . ' decimal');
		}
	}
}
