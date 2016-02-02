<?php

class CachePlugin extends Zend_Cache {
	private static $_cache = null;

	/**
	 * Initialize cache.
	 *
	 * @param string $type
	 * @param int $lifetime
	 * @return Zend_Cache_Core|Zend_Cache_Frontend
	 */
	public static function init($type = 'Core', $lifetime = 7200) {
		if (self::$_cache === null) {
			$frontendOptions = array(
				'lifetime' => $lifetime,
				'automatic_serialization' => true
			);
			$backendOptions = array(
				'cache_dir' => APPLICATION_PATH . '/../cache/',
				'hashed_directory_level' => 1,
				'hashed_directory_perm' => 0777,
				'cache_file_perm' => 0777
			);

			self::$_cache = Zend_Cache::factory($type, 'File', $frontendOptions, $backendOptions);
		}

		return self::$_cache;
	}
}
