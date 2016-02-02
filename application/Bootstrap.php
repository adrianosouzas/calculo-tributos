<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
		$this->bootstrap('view');
    }

	protected function _initLocale()
    {
		$translate = new Zend_Translate(
		    array(
		        'adapter' => 'csv',
		        'content' => APPLICATION_PATH . '/languages/',
		        'locale'  => 'pt_BR',
		    	'scan' => Zend_Translate::LOCALE_DIRECTORY,
				'delimiter' => ','
		    )
		);

		Zend_Registry::set('Zend_Translate', $translate);
	}

	protected function _initLayoutHelper()
    {
		$this->bootstrap('frontController');

		$layout = Zend_Controller_Action_HelperBroker::addHelper(new Zend_Module());
	}

//	protected function _initRewrite()
//    {
//		$front = Zend_Controller_Front::getInstance();
//		$router = $front->getRouter();
//		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'production');
//		$router->addConfig($config, 'routes');
//	}

	protected function _initSession()
    {
		Zend_Session::start();
	}
}


class Zend_Module extends Zend_Controller_Action_Helper_Abstract
{
	public function preDispatch()
    {
		$request = $this->getRequest();
		$module = $request->getModuleName();
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');

		$viewRenderer->init();

		$layout = Zend_Layout::getMvcInstance();
		$layoutsDir = $layout->getLayoutPath();
		if(file_exists($layoutsDir . DIRECTORY_SEPARATOR . $module . ".phtml")) {
		    $layout->setLayout($module);
		} else {
		    $layout->setLayout("layout");
		}

		if ($request->isXmlHttpRequest()) {
			Zend_Layout::getMvcInstance()->disableLayout();
		}

		$view = $viewRenderer->view;
		$view->basePath = BASE_PATH;
		$view->baseUrl = $request->getBaseUrl();
		$view->module = $request->getModuleName();
		$view->controller = $request->getControllerName();
		$view->action = $request->getActionName();
	}
}
