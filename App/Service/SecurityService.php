<?php

namespace App\Service;

use App\Entity\User;
use App\Utils\Tools;
use App\Repository\UserRepository;
use Mithridatem\Validation\Validator;
use Mithridatem\Validation\Exception\ValidationException;
use App\Service\MediaService;
use App\Utils\Logger;

class SecurityService
{
    private UserRepository $userRepository;
    private MediaService $mediaService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->mediaService = new MediaService();
    }

    /**
     * Methode pour ajouter un compte en BDD
     * @param array $post (super globale POST)
     * @return array resultat (message + erreurs)
     */
    public function saveUser(array $post): array
    {
        $errors = [];
        if (empty($post["pseudo"])) {
            $errors["pseudo"] = "Le pseudo est obligatoire";
        }
        if (empty($post["email"])) {
            $errors["email"] = "L'email est obligatoire";
        }
        if (empty($post["password"])) {
            $errors["password"] = "Le mot de passe est obligatoire";
        }
        if (empty($post["confirm-password"])) {
            $errors["confirm-password"] = "La confirmation est obligatoire";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        Tools::sanitize_array($post);

        if ($post["password"] != $post["confirm-password"]) {
            return ["errors" => ["confirm-password" => "Les mots de passe ne sont pas identiques"]];
        }
        if (!filter_var($post["email"], FILTER_VALIDATE_EMAIL)) {
            return ["errors" => ["email" => "L'email est invalide"]];
        }
        if ($this->userRepository->isUserExists($post["email"], $post["pseudo"])) {
            return ["errors" => ["_form" => "Le compte existe deja en BDD"]];
        }

        $user = new User();
        $user
            ->setEmail($post["email"])
            ->setPseudo($post["pseudo"])
            ->setFirstname($post["firstname"])
            ->setLastname($post["lastname"])
            ->setPassword($post["password"])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRoles("ROLE_USER");
        try {
            $validator = new Validator();
            $validator->validate($user);
        } catch(ValidationException $e) {
            return ["errors" => ["_form" => $e->getMessage()]];
        }

        $hash = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $user->setPassword($hash);
        if (isset($_FILES["img"]) && !empty($_FILES["img"]["tmp_name"])) {
            try {
                $media = $this->mediaService->addMedia($_FILES["img"]);
            } catch(\Exception $e) {
                Logger::error("SecurityService.addMedia failed", ["error" => $e->getMessage()]);
                return ["errors" => ["img" => "Erreur lors de l'upload de l'image"]];
            }
        } else {
            $media = $this->mediaService->getDefaultImg();
        }
        
        $user->setMedia($media);

        if ($this->userRepository->save($user) === null) {
            return ["errors" => ["_form" => "Erreur lors de la creation du compte"]];
        }
        return ["message" => "Le compte a ete ajoute en BDD"];
    }

    /**
     * Methode pour se connecter
     * @param array $post (super globale POST)
     * @return array resultat (message + erreurs)
     */
    public function authenticate(array $post): array
    {
        $errors = [];
        if (empty($post["email"])) {
            $errors["email"] = "L'email est obligatoire";
        }
        if (empty($post["password"])) {
            $errors["password"] = "Le mot de passe est obligatoire";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        Tools::sanitize_array($post);
        $user = $this->userRepository->findByEmail($post["email"]);

        if (!isset($user) || !password_verify($post["password"], $user->getPassword())) {
            return ["errors" => ["_form" => "Les informations de connexion sont invalides"]];
        }

        $_SESSION["user"] = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "pseudo" => $user->getPseudo(),
            "roles" => $user->getRoles(),
            "img" => $user->getMedia()
        ];
        return ["message" => "Connecte"];
    }
}
