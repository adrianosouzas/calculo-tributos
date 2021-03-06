<?php
/**
 * iZi Zend Generator
 *
 * @category   iZi
 * @author     Gustavo Henrique Conci
 * @copyright  Copyright (c) 2011 iZi (http://www.izisistemas.com.br)
 * @version    $Id: ##FORM##.php ##DATAHORA## $
 */

/**
 * This class is generated by the iZi Zend Generator
 * and possible changes will be lost the next generation.
 */
abstract class ##FORM## extends Zend_Form {
	
	private $_values;
	
	private static $_forms = array();
	
	/**
	 * Get Form from Cache!
	 * 
	 * @param string $form
	 * @param AbstractModel|array $values
	 * @return AbstractForm
	 */
	public static function get($form = '', $values = null) {
		if (empty($form))
			return null;
		
		$cache = CachePlugin::init('Core', is_null($values) ? null : 7200);
		$name = 'form_'.$form;
		if (!is_null($values)) $name .= '_' . md5(print_r($values, true));
		
		if (isset(self::$_forms[$name]))
			return self::$_forms[$name];
		else if (($object = $cache->load($name)) === false) {
			$class = ucwords(preg_replace('/(_|\-)/', ' ', $form));
			$class = str_replace(' ', '', $class) . 'Form';

			$object = new $class($values);
			$cache->save($object, $name);
		}
		
		self::$_forms[$name] = $object;

		return self::$_forms[$name];
	}
	
	public function general() {}
	
	public function config() {}
	
	public function __construct($values = null, $options = null) {
		$this->_values = $values;
		
		parent::__construct($options);
	}
	
	public function init() {
		$this->addElement('submit', 'submit', array('label' => 'Enviar', 'class' => 'submit', 'decorators' => array('ViewHelper')));
		
		$this->general();

		$this->config();
		
		$this->initPopulate($this->_values);
		
		$this->displayGroup($this->getElements(), $this->getName());
	}
	
	public function initPopulate($values) {
		if ($values != null) {
			if (!is_array($values))
				$values = $values->toArray();
			
			$this->_values = $values;
			$this->populate($this->_values);
		}
	}
	
	public function loadDefaultDecorators() {
		parent::loadDefaultDecorators();
		
		$this->setDecorators(array('FormElements', 'Fieldset', 'Form'))
			->setDisplayGroupDecorators(array('FormElements', array('HtmlTag', array('tag' => 'ul'))));
		$this->setAttrib('id', 'form-' . $this->getName());
		$this->getDecorator('Fieldset')->setOption('id', 'fieldset-' . $this->getName());
		
		$this->setElementsBelongTo($this->getName());
	}
	
	public function isValid($data) {
		$this->set($data);
		$values = $this->getValues(true);
		
		return parent::isValid($values);
	}
	
	public function displayGroup($elements, $name) {
		$this->setElementDecorators(array(array('ViewScript', array('viewScript' => 'elements/default.phtml'))));
		
		$displayGroup = array();
		foreach ($elements as $element) {
			if ($element instanceof Zend_Form_Element_Hidden)
				$element->setDecorators(array('ViewHelper'));
			elseif ($element instanceof Zend_Form_Element_Submit) {
			 	if (file_exists(APPLICATION_PATH . '/views/scripts/elements/submit.phtml'))
					$element->setDecorators(array(array('ViewScript', array('viewScript' => 'elements/submit.phtml'))));
				else
					$element->setDecorators(array('ViewHelper'));
			} elseif (array_key_exists($element->getName(), $this->_order)) {
				$type = strtolower(str_replace('form', '', $element->helper));
				
				if (file_exists(APPLICATION_PATH . '/views/scripts/elements/' . $this->getName() . '/' . $element->getName() . '.phtml'))
					$element->setDecorators(array(array('ViewScript', array('viewScript' => 'elements/' . $this->getName() . '/' . $element->getName() . '.phtml'))));
				elseif (file_exists(APPLICATION_PATH . '/views/scripts/elements/' . $this->getName() . '/' . $type . '.phtml'))
					$element->setDecorators(array(array('ViewScript', array('viewScript' => 'elements/' . $this->getName() . '/' . $type . '.phtml'))));
				elseif (file_exists(APPLICATION_PATH . '/views/scripts/elements/' . $element->getName() . '.phtml'))
					$element->setDecorators(array(array('ViewScript', array('viewScript' => 'elements/' . $element->getName() . '.phtml'))));
				elseif (file_exists(APPLICATION_PATH . '/views/scripts/elements/' . $type . '.phtml'))
					$element->setDecorators(array(array('ViewScript', array('viewScript' => 'elements/' . $type . '.phtml'))));
				elseif (!$element instanceof Zend_Form_Element_File)
					$element->setDecorators(array(array('ViewScript', array('viewScript' => 'elements/default.phtml'))));
				
				$displayGroup[] = $element->getName();
			} else {
				$element->setDecorators(array());
			}
		}
		
		$this->getElement('submit')->setOrder($this->count()+1);
		
		if ($displayGroup)
			$this->addDisplayGroup($displayGroup, $this->getName(), array('disableLoadDefaultDecorators' => true));
	}
	
	public function getDbOptions($table, $empty_value = '', $where = null) {
		$options = array('' => $empty_value);
		$fetch = AbstractDbTable::get($table)->fetchAll($where);
		foreach ($fetch as $item) {
			$options[$item->id] = $item;
		}
		return $options;
	}
	
	public function save() {
		$elements = $this->getElements();
		$model = AbstractModel::get($this->getName());
		$primary = $model->primary();
		$metadata = $model->getTable()->info('metadata');
		
		$edit = false;
		$values = array();
		foreach ($elements as $element) {
			$id = $element->getName();
			$value = (method_exists($element, 'getDbValue') ? $element->getDbValue() : $element->getValue());
			if (in_array($id, $primary) && $value) {
					$edit = $value;
			} else {
				if ($value)
					$values[$id] = $value;
				elseif (isset($metadata[$id]))
					$values[$id] = $metadata[$id]['DEFAULT'];
			}
		}

		if ($edit)
			$model = $model->getTable()->findOneById($edit);
		
		try {
			$model->setFromArray($values);
			return $model->save();
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
	
	public function set($values) {
		if ($values) {
			if (!is_array($values))
				$values = $values->toArray();
				
			if ($this->_values)
				$values = array_merge($this->_values, $values);
			else
				$this->initPopulate($values);
			
			if ($this->populate($values)) {
				return $this;
			}
		}
    }
    
    /**
	 * @return null|Zend_Controller_Request_Abstract
     */
    public function getRequest() {
    	return Zend_Controller_Front::getInstance()->getRequest();
    }

    public function isNew() {
    	if (isset($this->_values['id']) && $this->_values['id'])
    		return false;
    	else
    		return true;
    }
}