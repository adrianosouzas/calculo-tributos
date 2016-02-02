<?php

class Izi_Form_Element_Tinymce extends Zend_Form_Element_Textarea {

	public function init() {
		$this->setAttrib('class', $this->getAttrib('class') . ' tinymce');
	}
	
	public function getValue() {
		return stripslashes(parent::getValue());
	}
}