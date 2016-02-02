<?php

class ProdutoController extends AbstractController
{
	public function preDispatch()
    {
		parent::preDispatch();
		$this->addTitle('Produtos');
		$this->addCrumb('Produtos', $this->getBaseUrl() . '/');
	}
	
	public function indexAction()
    {
        $produtos = AbstractDbTable::get('produto');
        $this->view->produtos = $produtos->fetchAll();
	}
}
