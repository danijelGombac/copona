<?php

namespace Copona\System\Database;

class Database
{
    /**
     * @var AbstractDatabaseAdapters
     */
    private $adapter;

    public function __construct($adapter, Array $configs)
    {
        if (get_parent_class($adapter) == AbstractDatabaseAdapters::class) {
            $this->adapter = new $adapter($configs);
        } else {
            throw new DatabaseException($adapter . ' must extends of ' . AbstractDatabaseAdapters::class);
        }
    }

    public function query($sql, $params = array())
    {
        return $this->adapter->query($sql, $params);
    }

    public function escape($value)
    {
        return $this->adapter->escape($value);
    }

    public function countAffected()
    {
        return $this->adapter->countAffected();
    }

    public function getLastId()
    {
        return $this->adapter->getLastId();
    }

    public function connected()
    {
        return $this->adapter->connected();
    }
}