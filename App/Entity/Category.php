<?php

namespace App\Entity;

use Mithridatem\Validation\Attributes\NotBlank;
use Mithridatem\Validation\Attributes\Length;

class Category extends Entity
{
    private ?int $id;
    #[NotBlank]
    #[Length(2, 50)]
    private string $name;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(string $name = "")
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function __set($name, $value)
    {
        if ($name == "created_at") {
            $this->createdAt = new \DateTimeImmutable($value);
        } 
        if ($name == "updated_at") {
            $this->updatedAt = new \DateTimeImmutable($value);
        }
    }

    public static function hydrate(array $data): self 
    {
        $category = new Category($data["name"] ?? "");
        foreach ($data as $key => $value) {
            //assignation des dates
            if ($key == "created_at" || $key == "createdAt") {
                $category->setCreatedAt(new \DateTimeImmutable($value));
            }
            if ($key == "updated_at" || $key == "updatedAt") {
                $category->setUpdatedAt(new \DateTimeImmutable($value));
            }
        }
        return $category;
    }
}
