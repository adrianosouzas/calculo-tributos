<?php

class Izi_Form_Element_Password extends Zend_Form_Element_Password {
	
	public function getDbValue() {
		return md5($this->getValue());
	}
}