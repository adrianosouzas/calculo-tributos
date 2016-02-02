<?php

abstract class AbstractController extends Zend_Controller_Action
{
	protected $_auth;
    protected $_flashMessenger;
	protected $_title;
    protected $_message;
	protected $_breadcrumb;

	public function init() {
		$this->_title = array('Cálculo de Tributos');
        $this->_flashMessenger = $this->getHelper('FlashMessenger');
        $this->_breadcrumb = array('Home' => $this->getRequest()->getBaseUrl());
		$this->_auth = Zend_Auth::getInstance();
	}

	public function preDispatch()
    {
		if (($this->getRequest()->isXmlHttpRequest() || $this->getRequest()->getParam('ajax', false))) {
			Zend_Layout::getMvcInstance()->disableLayout();
			$this->view->ajax = 1;
		} else {
			$this->view->ajax = 0;
		}
	}

	public function postDispatch()
    {
        $this->view->flash_message = $this->getFlashMessages();
        $this->view->title = $this->_title;
		$this->view->breadcrumb = $this->_breadcrumb;
		$this->view->auth = $this->_auth->getIdentity();
	}

	public function addTitle($title)
    {
		$this->_title[] = $title;
	}

	public function addCrumb($crumb, $link = null)
    {
		if (is_array($crumb)) {
            foreach ($crumb as $id => $item) {
                $this->_breadcrumb[stripslashes($id)] = $item;
            }
        } else {
            $this->_breadcrumb[stripslashes($crumb)] = $link;
        }
	}

    public function addMessage($message, $type = 'alert')
    {
        $this->_flashMessenger->addMessage(array('type' => $type, 'message' =>  $message));
    }

    public function addMessageArray($array, $type = 'alert')
    {
        foreach ($array as $message) {
            $this->addMessage($message, $type);
        }
    }

    public function addMessageItemNotFound()
    {
        $this->addMessage('Item não foi encontrado ou não existe!', 'error');
    }

    public function addMessageRequired()
    {
        $this->addMessage('Campos obrigatórios devem ser preenchidos!', 'error');
    }

    public function getFlashMessages()
    {
        $flash_message = array_merge(
            $this->_flashMessenger->getMessages(),
            $this->_flashMessenger->getCurrentMessages()
        );
        $this->_flashMessenger->clearCurrentMessages();

        if (count($flash_message)) {
            return $flash_message;
        } else {
            return false;
        }
    }

	public function getReferer()
    {
		$referer = false;

		if (isset($_SERVER['HTTP_REFERER']))
			$referer = $_SERVER['HTTP_REFERER'];

		return $referer;
	}

	public function getBaseUrl()
    {
		return $this->getRequest()->getBaseUrl();
	}

	public function getBasePath()
    {
		return BASE_PATH;
	}

	public function paginator($data, $count_per_page = 5, $param = 'pagina')
    {
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('includes/paginacao.phtml');

		$paginacao = Zend_Paginator::factory($data);
		$paginacao->setCurrentPageNumber($this->getRequest()->getParam($param));
		$paginacao->setItemCountPerPage($count_per_page);

		return $paginacao;
	}
}
