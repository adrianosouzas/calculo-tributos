<?php

abstract class AdminAbstractController extends AbstractController
{
	protected $_name;

	protected $_table;

	protected $_form;

	protected $_cols;

	protected $_select;

	public function init()
    {
		parent::init();

		$request = $this->getRequest();
		$controller = $request->getControllerName();

		$this->_title = array('Cálculo de Tributos - Administraçao');

		$this->_auth = Zend_Auth::getInstance();
 		if (!$this->_auth->getIdentity() && $controller != 'login')
			$this->_redirect('/admin/login');

		Zend_Layout::getMvcInstance()->setLayout('admin');
	}

	public function preDispatch()
    {
		$request = $this->getRequest();

		$this->addTitle($this->_name);

		$this->view->form = $this->_form;
		$this->view->name = $this->_name;
		$this->view->table = preg_replace('/\-/', '_', $this->_table);

		if (in_array($request->getActionName(), array('index', 'find')) && !$this->_select && $this->_table)
			$this->_select = AbstractDbTable::get($this->_table)->select()->order('id desc');
	}

	public function indexAction()
    {
		$this->view->cols = $this->_cols;
        $this->view->list = $this->paginator($this->_select, 10);
	}

	public function newAction() {}

	public function editAction()
    {
		$id = $this->getRequest()->getParam('id', false);
		if ($id) {
			$item = AbstractDbTable::get($this->_table)->findOneById($id);
			$this->view->form = $this->_form->set($item);
		}
	}

	public function delAction()
    {
		$id = $this->getRequest()->getParam('id', false);
		if ($id) {
			$dbtable = AbstractDbTable::get($this->_table);
			$item = $dbtable->findOneById($id);
			if ($item) {
				if ($dbtable->delete('id = ' . $item->id)) {
					$nome = 'item';
					if (isset($item->nome))
						$nome = '\"' . $item->nome . '\"';

					$this->addMessage('Exclusão do ' . $nome . ' efetuado com sucesso!');
				} else
					$this->addMessage('Erro ao excluir \"' . $nome . '\"!');
			} else
				$this->addMessageItemNotFound();
		} else
			$this->addMessageItemNotFound();

		$this->_redirect('/admin/' . preg_replace('/\_/', '-', $this->_table));
	}

	public function saveAction()
    {
		$data = $this->getRequest()->getParam($this->_table);
		$form = $this->_form;
		if (isset($data['id'])) {
			$item = AbstractDbTable::get($this->_table)->findOneById($data['id']);
			if ($item)
				$form->set($item);
		}

		if ($form->isValid($data)) {
			if (!$form->save())
				foreach ($form->getErrorMessages() as $message)
					$this->addMessage($message, 'danger');
			else {
				if ($data['id'] != '')
					$this->addMessage('Editado com sucesso!', 'success');
				else
					$this->addMessage('Cadastrado com sucesso!', 'success');

				$this->_redirect('admin/' . str_replace('_', '-', $this->_table));
				exit;
			}
		}

		$this->view->form = $form;
		$this->render('edit');
	}

	public function findAction()
    {
		$session = new Zend_Session_Namespace('find');
		$data = $this->getRequest()->getParam($this->_table, false);
		if (!$data && isset($session->data))
			$data = $session->data;

		if ($data) {
			$session->data = $data;

			$this->_form->set($data);

			$this->view->form = $this->_form;
			$this->view->cols = $this->_cols;

			$this->view->list = $this->paginator(
				AbstractDbTable::get($this->_table)
					->findByForm($this->_form, $this->_select)
			, 10);

			$this->render('index');
		}
	}
}
