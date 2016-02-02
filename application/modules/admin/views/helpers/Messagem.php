<?php

class Zend_View_Helper_Messagem extends Zend_View_Helper_Abstract
{
	public function messagem($flash_message)
    {
        $html = '';
        foreach ($flash_message as $messages) {
            $html .= '<div class="alert alert-'. $messages['type'] .'">';
            $html .= ''.$messages['message'] .'';
            $html .= '</div>';
        }

		return $html;
	}
}
