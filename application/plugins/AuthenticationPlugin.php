<?php
class AuthenticationPlugin implements Zend_Auth_Adapter_Interface {
	
	private $_auth;
	private $_values;
	private $_where;
	
	public function __construct($values, $where = '') {
		$this->_values = $values;
		$this->_where = $where;
		$this->_auth = Zend_Auth::getInstance();
		
		$this->authenticate();
	}
	
	public function authenticate() {
		$dbTable = 'usuario';
		
		$authAdapter = new Zend_Auth_Adapter_DbTable();
		$authAdapter
		    ->setTableName($dbTable)
		    ->setIdentityColumn('email')
		    ->setCredentialColumn('senha')
		    ->setIdentity($this->_values['email'])
			->setCredential(md5($this->_values['senha']))
			->getDbSelect();
		
		$result = $this->_auth->authenticate($authAdapter);
		if ($result->isValid()) {
			$data = $authAdapter->getResultRowObject($columns);
			$this->_auth->getStorage()->clear();
			$this->_auth->getStorage()->write($data);
		}
	}
}