<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Entity;
use App\Entity\Media;
use DateTimeImmutable;

class MediaRepository extends AbstractRepository
{
    public function find(int $id): ?Media 
    {
        try {
            $sql = "SELECT m.id, m.url, m.alt, m.created_at FROM media AS m WHERE m.id = ?";
            $req = $this->connect->prepare($sql);
            $req->bindParam(1, $id, \PDO::PARAM_INT);
            $req->execute();
            //$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Media::class);
            
            $media = $req->fetch(\PDO::FETCH_ASSOC);
            $newMedia = new Media($media["url"], $media["alt"], new DateTimeImmutable($media["created_at"]));
            $newMedia->setId($media["id"]);
 
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
        return $newMedia;
    }

    public function findAll(): array 
    {
        return [];
    }

    public function save(Entity $entity): Media
    {
        try {
            $sql = "INSERT INTO media(`url`, alt, created_at) VALUE(?,?,?)";
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $entity->getUrl(), \PDO::PARAM_STR);
            $req->bindValue(2, $entity->getAlt(), \PDO::PARAM_STR);
            $req->bindValue(3, $entity->getCreatedAt()->format('Y-m-d'), \PDO::PARAM_STR);
            $req->execute();
            $id = $this->connect->lastInsertId();
            $entity->setId($id);
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
        return $entity;
    }
}