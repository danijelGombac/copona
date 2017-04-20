<?php

namespace Copona\System\Database;

use Illuminate\Database\Eloquent\Model;

class ModelBase extends Model
{
    public function __construct($registry)
    {
        $this->connection = $registry->get('config')->get('db_connect_name');
    }
}