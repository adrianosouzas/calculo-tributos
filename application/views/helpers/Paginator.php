<?php

class Zend_View_Helper_Paginator extends Zend_View_Helper_Abstract {

	public function paginator($data, $config = array(), $style = 'Sliding') {
		if (!isset($config['url'])) {
			$front = Zend_Controller_Front::getInstance();
			$request = $front->getRequest();
			$module = $request->getModuleName();
			$controller = $request->getControllerName();
			$action = $request->getActionName();
			
			$getParams = $request->getParams();
			unset($getParams['module'], $getParams['controller'], $getParams['action'], $getParams['pagina']);
			
			if ($module == 'admin')
				unset($getParams[str_replace('-', '_', $controller)]); 
			
			$params = '';
			foreach ($getParams as $param => $param_value) {
				$params .= '/' . $param . '/' . $param_value;
			}
			
			if ($module == 'default')
				$config['url'] = $front->getBaseUrl();
			else
				$config['url'] = $front->getBaseUrl() . '/' . $module;
			
			$config['url'] .= '/' . $controller . '/' . $action . $params . '/pagina/';
		}
		
		return $this->view->paginationControl($data, $style, 'includes/paginacao.phtml', $config);
	}
}