<?php

class TranslatePlugin {
	public static function get($sring) {
		$translator = Zend_Registry::get('Zend_Translate');
		$string = $translator->translate($sring);

		return $string;
	}
}