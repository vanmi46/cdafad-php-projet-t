<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Entity;
use App\Entity\User;
use App\Utils\Logger;

class UserRepository extends AbstractRepository
{
    public function find(int $id): ?User
    {
        return new User();
    }

    public function findAll(): array
    {
        return [];
    }

    public function save(Entity $entity): ?User
    {
        try {
            //2 Ecrire la requête SQL
            $sql = "INSERT INTO users(firstname, lastname, pseudo, email, `password`, roles, created_at, media_id)
            VALUE(?,?,?,?,?,?,?,?)";
            //3 Préparer la requête
            $req = $this->connect->prepare($sql);
            //4 Assigner les paarmètres(bindParam)
            $req->bindValue(1, $entity->getFirstname(), \PDO::PARAM_STR);
            $req->bindValue(2, $entity->getLastname(), \PDO::PARAM_STR);
            $req->bindValue(3, $entity->getPseudo(), \PDO::PARAM_STR);
            $req->bindValue(4, $entity->getEmail(), \PDO::PARAM_STR);
            $req->bindValue(5, $entity->getPassword(), \PDO::PARAM_STR);
            $req->bindValue(6, $entity->getRoles(), \PDO::PARAM_STR);
            $req->bindValue(7, $entity->getCreatedAt()->format('Y-m-d'), \PDO::PARAM_STR);
            $req->bindValue(8, $entity->getMedia()->getId(), \PDO::PARAM_INT);
            //5 exécuter la requête
            $req->execute();
            //6 récupérer l'id
            $id = $this->connect->lastInsertId();
            $entity->setId($id);
        }
        catch(\Exception $e){
            Logger::error("UserRepository.save failed", ["error" => $e->getMessage()]);
            return null;
        }
        return $entity;
    }

    public function isUserExists(string $email, string $pseudo): bool
    {
        try {
            //2 Ecrire la requête SQL
            $sql = "SELECT u.id FROM users AS u WHERE u.email = ? OR u.pseudo = ?";
            //3 Préparer la requête
            $req = $this->connect->prepare($sql);
            //4 Assigner les paarmètres(bindParam)
            $req->bindParam(1, $email, \PDO::PARAM_STR);
            $req->bindParam(2, $pseudo, \PDO::PARAM_STR);
            //5 exécuter la requête
            $req->execute();
            //6 récupérer la réponse (SELECT)
            $user = $req->fetch();
            if (empty($user)) {
                return false;
            } 
            return true;
        } catch(\PDOException $e) {
            Logger::error("UserRepository.isUserExists failed", ["error" => $e->getMessage()]);
            return false;
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            //2 Ecrire la requête SQL
        $sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.pseudo, u.password, u.roles, u.created_at, m.url AS url_image, 
            m.alt AS alt_image, m.created_at AS created_at_image, m.id AS id_image
            FROM users AS u INNER JOIN media AS m ON u.media_id = m.id
            WHERE u.email = ?";
            //3 Préparer la requête
            $req = $this->connect->prepare($sql);
            //4 Assigner les paarmètres(bindParam)
            $req->bindParam(1, $email, \PDO::PARAM_STR);
            //5 exécuter la requête
            $req->execute();
            //6 récupérer la réponse (SELECT)
            $user = $req->fetch(\PDO::FETCH_ASSOC);
            //Hydratation en objet User
            if (!empty($user)) {
                $user = User::hydrate($user);
            } else {
                $user = null;
            }

        } catch(\PDOException $e){
            Logger::error("UserRepository.findByEmail failed", ["error" => $e->getMessage()]);
            return null;
        }
        return $user;
    }
}
