<?php

namespace App\Repository;

use App\Entity\Entity;
use App\Database\Mysql;

abstract class AbstractRepository
{
    protected \PDO $connect;

    //Constructeur
    public function __construct()
    {
        $this->connect = Mysql::connectBdd();
    }

    //Méthodes
    public abstract function find(int $id): ?Entity;

    public abstract function findAll(): array;

    public abstract function save(Entity $entity): ?Entity;

}
