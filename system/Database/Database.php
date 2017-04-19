<?php

namespace Copona\System\Database;

use Copona\System\Database\Adapters\Eloquent;

class Database
{
    /**
     * @var AbstractDatabaseAdapters
     */
    private $adapter;

    public function __construct(Array $configs)
    {
        $this->adapter = new Eloquent($configs);
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