<?php

class Admin_TributoController extends AdminAbstractController {
	
	public function init() {
		parent::init();
		
		$this->_name = 'Tributos';		
		$this->_table = 'tributo';
		$this->_form = new TributoForm();
		
		$this->_cols = array(
			'nome' => 'string',
			'imposto' => 'string'
		);
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id', false);
		if ($id) {
			$item = AbstractDbTable::get($this->_table)->findOneById($id);
			
			$this->view->form = $this->_form->set($item);
		}
	}
	
	public function saveAction() {
		$data = $this->getRequest()->getParam($this->_table, false);
		
		$item = false;
		$item = AbstractDbTable::get($this->_table)->findOneById($data['id']);
		
		$form = $this->_form;
		if (isset($data['id']))
			$form->set($item);
		
		if ($form->isValid($data)) {
            $form->save();

            if ($data['id'] != '')
                $this->addMessage('Tributo editado com sucesso!', 'success');
            else
                $this->addMessage('Tributo cadastrado com sucesso!', 'success');

            $this->_redirect('admin/tributo');
		}
		$this->addMessage('Os campos obrigatórios devem estar preenchidos!', 'warning');
		$this->view->form = $form;
	}
}