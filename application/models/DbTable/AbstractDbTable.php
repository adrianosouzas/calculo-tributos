<?php

class AbstractDbTable extends Zend_Db_Table_Abstract
{
    private static $_dbtables = array();
    public $_cache = false;

    public function __construct($config = array())
    {
        parent::__construct($config);

        if (!$this->_cache || $this->_cache === TRUE)
            $this->_cache = CachePlugin::init();
    }

    public static function getSingleton($dbtable = '')
    {
        if (empty($dbtable))
            return null;

        if (isset(self::$_dbtables[$dbtable]))
            return self::$_dbtables[$dbtable];

        $class = ucwords(preg_replace('/(_|\-)/', ' ', $dbtable));
        $class = str_replace(' ', '', $class) . 'DbTable';

        self::$_dbtables[$dbtable] = new $class();

        return self::$_dbtables[$dbtable];
    }

    public static function get($model = '')
    {
        return self::getSingleton($model);
    }

    public function findBy($column, $value)
    {
        return $this->fetchAll(
            $this->select()->where($column . ' = ?', $value)
        );
    }

    public function findOneBy($column, $value)
    {
        return $this->fetchRow(
            $this->select()->where($column . ' = ?', $value)
        );
    }

    public function findBySql($where)
    {
        return $this->fetchAll(
            $this->select()->where($where)
        );
    }

    public function findOneBySql($where)
    {
        return $this->fetchRow(
            $this->select()->where($where)
        );
    }

    public function findByForm($form, $select)
    {
        $cols = array();

        preg_match('/select (.+) from/i', $select, $matches);
        if (count($matches)) {
            preg_match_all('/([a-z`.*\s]+)/i', $matches[1], $matches);
            if (count($matches)) {
                $matches[1] = str_replace('`', '', $matches[1]);
                foreach ($matches[1] as $item) {
                    $item = trim($item);

                    preg_match('/\.([a-z\*\_]+)/i', $item, $matches_col);
                    if (count($matches_col)) {
                        preg_match('/(.+) as (.+)/i', $item, $matches_item);
                        if (count($matches_item)) {
                            $cols[$matches_item[2]] = array(
                                'col' => $matches_col[1],
                                'name' => $matches_item[1]
                            );
                        } else {
                            $cols[$matches_col[1]] = array('name' => $item);
                        }
                    }
                }
            }
        }
        preg_match_all('/on ([a-z\`\.\*\_]+) /i', $select, $matches_inner);
        if (count($matches_inner)) {
            foreach ($matches_inner[1] as $item) {
                preg_match('/\.([a-z\*\_]+)/i', $item, $matches_col);
                if (count($matches_col)) {
                    $cols[$matches_col[1]] = array(
                        'col' => $matches_col[1],
                        'name' => $item
                    );
                }
            }
        }

        foreach ($form->getElements() as $item) {
            $name = $item->getName();
            foreach ($cols as $col => $col_value) {
                if ($col == $name || isset($col_value['col']) && $col_value['col'] == $name) {
                    $name = $col_value['name'];
                    break;
                }
            }

            if ($item instanceof Izi_Form_Element_Date && $item->getValue()) {
                if (preg_match('/(.+)_ini$/', $name, $matches)) {
                    $select->where($matches[1] . ' >= ?', $item->getDbValue());
                } elseif (preg_match('/(.+)_end$/', $name, $matches)) {
                    $select->where($matches[1] . ' <= ?', $item->getDbValue());
                }
            } elseif ($item instanceof Zend_Form_Element_Text && $item->getValue()) {
                $select->where($name . ' like ?', '%' . $item->getValue() . '%');
            } elseif ($item instanceof Zend_Form_Element_Select && $item->getValue()) {
                $select->where($name . ' = ?', $item->getValue());
            }
        }

        return $this->fetchAll($select);
    }

    public function insert(array $data)
    {
        if ($this->_cache) {
            $this->_cache->clean(
                Zend_Cache::CLEANING_MODE_MATCHING_TAG
            );
        }

        return parent::insert($data);
    }

    public function delete($where)
    {
        if ($this->_cache) {
            $this->_cache->clean(
                Zend_Cache::CLEANING_MODE_MATCHING_TAG
            );
        }

        return parent::delete($where);
    }

    protected function _fetch(Zend_Db_Table_Select $select)
    {
        if ($this->_cache) {
            $cache_name = 'fetch_' . $this->_name . '_' . md5($select);
            if (($fetch = $this->_cache->load($cache_name)) === false) {
                $fetch = parent::_fetch($select);
                $this->_cache->save($fetch, $cache_name);
            }
        } else {
            $fetch = parent::_fetch($select);
        }

        return $fetch;
    }
}
