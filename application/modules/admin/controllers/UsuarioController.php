<?php

class Admin_UsuarioController extends AdminAbstractController {
	
	public function init() {
		parent::init();
		
		$this->_name = 'Usuários';		
		$this->_table = 'usuario';
		$this->_form = new UsuarioForm();
		
		$this->_cols = array(
			'email' => 'string'
		);
	}
}