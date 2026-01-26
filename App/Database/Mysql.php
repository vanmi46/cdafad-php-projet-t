<?php

namespace App\Database;

class Mysql
{
     /**
     * Méthode pour se connecter à la base de données MariaDB
     * @return \PDO L'objet PDO pour la connexion à la base de données
     */
    public static function connectBdd(): \PDO
    {
        return
            new \PDO(
                'mysql:host=' . $_ENV["DATABASE_HOST"] . ';dbname=' . $_ENV["DATABASE_NAME"] . '',
                $_ENV["DATABASE_USERNAME"],
                $_ENV["DATABASE_PASSWORD"],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
    }
}
