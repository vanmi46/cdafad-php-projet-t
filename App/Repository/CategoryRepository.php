<?php

namespace App\Repository;

use App\Entity\Entity;
use App\Entity\Category;
use App\Repository\AbstractRepository;
use App\Utils\Logger;

class CategoryRepository extends AbstractRepository
{
    public function find(int $id): ?Category
    {
        return new Category("");
    }

    public function findAll(): array
    {
        try {
            //1 Ecrire la requête
            $sql = "SELECT c.id, c.name, c.created_at FROM category AS c ORDER BY c.name";
            //2 préparer la requête
            $req = $this->connect->prepare($sql);
            //3 exécuter la requête
            $req->execute();
            //4 récupérer les données (fetchAll)
            $req->setFetchMode(\PDO::FETCH_CLASS| \PDO::FETCH_PROPS_LATE, Category::class);
            $categories = $req->fetchAll();
        } catch(\PDOException $e) {
            Logger::error("CategoryRepository.findAll failed", ["error" => $e->getMessage()]);
            return [];
        }
        return $categories;
    }

    public function save(Entity $entity): ?Category
    {
        try {
            //2 Ecrire la requête SQL
            $sql = "INSERT INTO category(`name`, created_at)
            VALUE (?,?)";
            //3 Préparer la requête
            $req = $this->connect->prepare($sql);
            //4 Assigner les paarmètres(bindParam)
            $req->bindValue(1, $entity->getName(), \PDO::PARAM_STR);
            $req->bindValue(2, $entity->getCreatedAt()->format('Y-m-d'), \PDO::PARAM_STR);
            //5 exécuter la requête
            $req->execute();
            //6 récupérer l'id
            $id = $this->connect->lastInsertId();
            $entity->setId($id);
        }
        catch(\Exception $e){
            Logger::error("CategoryRepository.save failed", ["error" => $e->getMessage()]);
            return null;
        }
        return $entity;
    }

    public function isCategoryExists(string $name): bool
    {
        try {
            //2 Ecrire la requête SQL
            $sql = "SELECT c.id FROM category AS c WHERE c.name = ?";
            //3 Préparer la requête
            $req = $this->connect->prepare($sql);
            //4 Assigner les paarmètres(bindParam)
            $req->bindParam(1, $name, \PDO::PARAM_STR);
            //5 exécuter la requête
            $req->execute();
            //6 récupérer la réponse (SELECT)
            $category = $req->fetch();
            if (empty($category)) {
                return false;
            } 
            return true;
        } catch(\PDOException $e) {
            Logger::error("CategoryRepository.isCategoryExists failed", ["error" => $e->getMessage()]);
            return false;
        }
    }
}   
