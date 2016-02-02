<?php

class Izi_Form_Element_Date extends Zend_Form_Element_Text {

	public $_format = 'dd/MM/yyyy';
	public $_db_format = 'yyyy-MM-dd';
	
	public function __construct($spec, $options = null) {
		if (isset($options['format'])) {
			$this->_format = $options['format'];
			unset($options['format']);
		}
		if (isset($options['db_format'])) {
			$this->_db_format = $options['db_format'];
			unset($options['db_format']);
		}
		
		parent::__construct($spec, $options);
	}
	
	public function init() {
		$this->setAttrib('class', 'date');
		$this->setValidators(array(
			array('Date', false, array('format' => $this->_format))
		));
	}
	
	public function setValue($value) {
		if ($value) {
			$date = new Zend_Date($value, $this->_db_format);
			return parent::setValue($date->toString($this->_format));
		}
	}
	
	public function getDbValue() {
		$value = $this->getValue();
		if ($value) {
			$date = new Zend_Date($value, $this->_format);
			
			return $date->toString($this->_db_format);
		}
	}
}