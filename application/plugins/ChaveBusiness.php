<?php
class ChaveBusiness {

	public static function chave($string) {
		$acentos = array(
			'a' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;|&ordf;/',
			'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
			'c' => '/&Ccedil;/',
			'c' => '/&ccedil;/',
			'e' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
			'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
			'i' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
			'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
			'n' => '/&Ntilde;/',
			'n' => '/&ntilde;/',
			'o' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;|&ordm;/',
			'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
			'u' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
			'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
			'y' => '/&Yacute;/',
			'y' => '/&yacute;|&yuml;/',
			'-' => '/\s/',
			's' => '/\$/',
			'' => '/[^a-zA-Z0-9\-]/'
		);

		return strtolower(preg_replace($acentos, array_keys($acentos), htmlentities($string, ENT_NOQUOTES, 'UTF-8')));
	}

}