<?php

class Admin_LoginController extends AdminAbstractController
{
	public function preDispatch()
    {
		$this->addTitle('Login');

		$this->view->form_login = new LoginForm(array('referer' => $this->getReferer()));
	}
	
	public function indexAction()
    {
		$this->_redirect('/admin/login/login');
	}
	
	public function loginAction()
    {
		$values = $this->getRequest()->getParam('login', false);
		if ($values && !$this->_auth->hasIdentity()) {
			if ($this->view->form_login->isValid($values)) {
				new AuthenticationPlugin($values);
				if ($this->_auth->hasIdentity())
					if ($data['referer'])
						$this->_redirect($data['referer']);
					else
						$this->_redirect('/admin/index');
				else
					$this->addMessage('Usuário ou senha inválida!', 'warning');
			}
		}
	}
	
	public function logoffAction()
    {
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		
		$this->_redirect('/admin');
	}
}