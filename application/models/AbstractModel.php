<?php

class AbstractModel extends Zend_Db_Table_Row
{
    private static $_models = array();

    public static function getSingleton($model = '')
    {
        if (empty($model))
            return null;

        if (isset(self::$_models[$model]))
            return self::$_models[$model];

        $class = ucwords(preg_replace('/(_|\-)/', ' ', $model));
        $class = str_replace(' ', '', $class) . 'Model';

        self::$_models[$model] = new $class();

        return self::$_models[$model];
    }

    public static function get($model = '')
    {
        $class = ucwords(preg_replace('/(_|\-)/', ' ', $model));
        $class = str_replace(' ', '', $class) . 'Model';

        return new $class();
    }

    public function primary()
    {
        return $this->_primary;
    }

    public function save()
    {
        $dbtable = $this->_table;
        if ($dbtable->_cache) {
            $dbtable->_cache->clean(
                Zend_Cache::CLEANING_MODE_MATCHING_TAG
            );
        }

        return parent::save();
    }

    public function __toString()
    {
        return isset($this->nome) ? $this->nome : (string) $this->id;
    }
}
