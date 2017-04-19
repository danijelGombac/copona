<?php

namespace Copona\System\Database;


interface DatabaseInterface
{
    public function __construct(Array $configs);

    public function query($sql);

    public function escape($value);

    public function countAffected();

    public function getLastId();

    public function connected();
}