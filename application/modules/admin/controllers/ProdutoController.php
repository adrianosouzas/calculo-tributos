<?php

class Admin_ProdutoController extends AdminAbstractController {
	
	public function init() {
		parent::init();
		
		$this->_name = 'Produtos';		
		$this->_table = 'produto';
		$this->_form = new ProdutoForm();
		
		$this->_cols = array(
			'nome' => 'string'
		);
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
        $data['chave'] = ChaveBusiness::chave($data['nome']);

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
}
