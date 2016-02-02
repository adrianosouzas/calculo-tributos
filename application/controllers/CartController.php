<?php

class CartController extends AbstractController
{
    private $_cart;
    public function init()
    {
        $this->_cart = new Zend_Session_Namespace('cart');
        if (!isset($this->_cart->items)) {
            $this->_cart->items = array();
        }
    }

    public function addAction()
    {
        $param = $this->getRequest()->getParam('produto', false);

        $key = array_search($param['id'], array_column($this->_cart->items, 'id'));
        if ($key === false) {
            $this->_cart->items[] = $param;
        } else {
            $this->_cart->items[$key]['quantidade'] += $param['quantidade'];
        }

        return $this->redirect('/');
    }

    public function delAction()
    {
        $this->view->cartModel = $this->_cartModel;
    }

    public function endAction()
    {
        $this->_cart->items = array();

        return $this->redirect('/');
    }
}
