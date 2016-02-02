<?php

class ErrorController extends AbstractController
{
	public function preDispatch()
    {
		$this->addTitle('Oopss!');
    	$this->addTitle('ocorreu um erro...');
	}

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        if (!$errors) {
            $this->view->error_message = '...ou você acessou a página de erro.';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->error_message = '...pois a página que procura não foi encontrada ou não existe.';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->error_message = '...inesperado na aplicação.';
                
                $mensagem = '<b>' . $errors->exception->getMessage() . '</b><br/><br/>';
                $mensagem .= '<b>Arquivo</b> ' . $errors->exception->getFile() . ', linha ' . $errors->exception->getLine() . '<br/><br/>';
                $mensagem .= '<b>Trace</b> ' . $errors->exception->getTraceAsString();
                die($mensagem);
                $assunto = 'ErrorHandler - ' . implode(' > ', $this->_title);
                $this->sendMail($mensagem, $assunto, 'adrianos_s@yahoo.com.br');
                
                break;
        }
    }
    
    public function logoffAction()
    {
    	$this->addTitle('Erro');
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}

