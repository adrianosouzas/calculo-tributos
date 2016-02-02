<?php

class IndexController extends AbstractController
{
    private $_totalImposto = 0;
    private $_totalCompra = 0;

	public function preDispatch()
    {
		parent::preDispatch();
		$this->addTitle('Home');
		$this->addCrumb('Home', $this->getBaseUrl() . '/');
	}
	
	public function indexAction()
    {
        $collection = AbstractDbTable::get('produto');
        $cartNamespace = new Zend_Session_Namespace('cart');

        $this->view->produtos = $collection->produtoLista();

        if (isset($cartNamespace->items) && count($cartNamespace->items)) {
            $this->view->cart = $this->getItemCart($cartNamespace->items);
            $this->view->impostos = $this->_totalImposto;
            $this->view->total = $this->_totalCompra;
        }
	}

    protected function getItemCart($cart)
    {
        $produtos = array();
        $collection = AbstractDbTable::get('produto');

        foreach($cart as $item) {
            $produto = $collection->produtoById($item['id']);
            $total = $produto->preco * $item['quantidade'];
            $imposto = $total * ($produto->imposto / 100);

            $produtos[] = array(
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => $item['quantidade'],
                'total' => $total,
                'imposto' => $imposto
            );
            $this->_totalImposto = $this->_totalImposto + $imposto;
            $this->_totalCompra = $this->_totalCompra + $total;
        }

        return $produtos;
    }
}
