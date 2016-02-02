<?php

class Izi_Form_Element_Chave extends Zend_Form_Element_Text {
	
	public $_table;
	public $_field;
	
	public function __construct($spec, $options = null) {
		$this->_table = $options['table'];
		
		if (isset($options['field']))
			$this->_field = $options['field'];
		else
			$this->_field = 'nome';
		
		parent::__construct($spec, $options);
	}
	
	public function getValue() {
		return $this->getDbValue();
	}
	
	public function getDbValue() {
		$value = false;
		if ($_REQUEST && isset($_REQUEST[$this->_table]))
			$value = $_REQUEST[$this->_table][$this->_field];
		if ($value)
			return $this->setChave($value);
	}
	
	public function setChave($value) {
		$values = false;
		if ($_REQUEST)
			$values = $_REQUEST[$this->_table];
		
		if ($values && isset($values) && $value) {
			$value = ChavePlugin::get($value);
			$dbtable = AbstractDbTable::get($this->_table);
			$select = $dbtable->select()
					->from($this->_table, array('id', 'chave'))
					->where('chave regexp ?', $value)
					->order('chave desc');
			
			$fetch = $dbtable->fetchAll($select);
			$count = 0;
			$array = array();
			if ($fetch->count()) {
				$count = $fetch->count();
				foreach ($fetch as $item)
					$array[$item->id] = $item->chave;
			
				if (array_key_exists($values['id'], $array))
					$value = $array[$values['id']];
				elseif (in_array($value, $array))
					$value = $value . '-' . ($count + 1);
			}
			
			return $value;
		}
		
		return $value;
	}
}