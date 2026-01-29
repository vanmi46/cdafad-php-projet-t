<?php

namespace App\Entity;

use Mithridatem\Validation\Attributes\NotBlank;
use Mithridatem\Validation\Attributes\Email;
use Mithridatem\Validation\Attributes\Length;
use Mithridatem\Validation\Attributes\Pattern;

class User extends Entity
{
    private ?int $id;
    private ?string $firstname;
    private ?string $lastname;
    #[NotBlank]
    #[Length(2,50)]
    private string $pseudo;
    #[NotBlank]
    #[Email]
    private string $email;
    #[NotBlank]
    #[Pattern('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/')]
    private string $password;
    private bool $status = true;
    private bool $active = true;
    private bool $deleted = false;
    private string $roles;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt;
    private ?Media $media;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    
    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
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

    public function getDeletedAt(): \DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;
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
        $user = new User();
        $media = new Media();
        foreach ($data as $key => $value) {
            switch ($key) {
                case "id":
                    $user->setId((int) $value);
                    break;
                case "firstname":
                    $user->setFirstname($value);
                    break;
                case "lastname":
                    $user->setLastname($value);
                    break;
                case "pseudo":
                    $user->setPseudo($value);
                    break;
                case "email":
                    $user->setEmail($value);
                    break;
                case "password":
                    $user->setPassword($value);
                    break;
                case "roles":
                    $user->setRoles($value);
                    break;
                case "status":
                    $user->setStatus((bool) $value);
                    break;
                case "active":
                    $user->setActive((bool) $value);
                    break;
                case "deleted":
                    $user->setDeleted((bool) $value);
                    break;
                case "created_at":
                case "createdAt":
                    $user->setCreatedAt(new \DateTimeImmutable($value));
                    break;
                case "updated_at":
                case "updatedAt":
                    $user->setUpdatedAt(new \DateTimeImmutable($value));
                    break;
                case "deleted_at":
                case "deletedAt":
                    $user->setDeletedAt(new \DateTimeImmutable($value));
                    break;
                case "url_image":
                    $media->setUrl($value);
                    break;
                case "alt_image":
                    $media->setAlt($value);
                    break;
                case "created_at_image":
                    $media->setCreatedAt(new \DateTimeImmutable($value));
                    break;
                case "id_image":
                    $media->setId((int) $value);
                    break;
                default:
                    // Ignore unknown keys to avoid dynamic properties.
                    break;
            }
        }
        $user->setMedia($media);
        
        return $user;
    }
}
