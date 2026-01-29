<?php

namespace App\Service;

use App\Entity\Entity;
use App\Entity\User;
use App\Entity\Media;
use App\Utils\Tools;
use App\Repository\UserRepository;
use Mithridatem\Validation\Validator;
use Mithridatem\Validation\Exception\ValidationException;
use App\Service\MediaService;
use DateTime;
use DateTimeImmutable;

class SecurityService
{
    //Attribut
    private UserRepository $userRepository;
    private MediaService $mediaService;

    //Constructeur
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->mediaService = new MediaService();
    }

    //Méthodes
    /**
     * Méthode pour ajouter un compte en BDD
     * @param array $post (super globale POST)
     * @return string $message de sortie
     */
    public function saveUser(array $post): string
    {
        //Test si les champs ne sont pas remplis
        if (
            empty($_POST["pseudo"]) ||
            empty($_POST["email"]) ||
            empty($_POST["password"]) ||
            empty($_POST["confirm-password"])
        ) {
            return "Veuillez remplir les champs du formulaire";
        }
        
        //Nettoyage des données
        Tools::sanitize_array($_POST);

        //Sinon si les 2 mots de passe sont différents
        if ($_POST["password"] != $_POST["confirm-password"]) {
            return "Les mots de passe ne sont pas identiques";
        }

        //Test si le compte existe déja
        if ($this->userRepository->isUserExists($_POST["email"], $_POST["pseudo"])) {
            return "Le compte existe déjà en BDD";
        }

        //créer un objet User
        $user = new User();
        //Set des attributs
        $user
            ->setEmail($_POST["email"])
            ->setPseudo($_POST["pseudo"])
            ->setFirstname($_POST["firstname"])
            ->setLastname($_POST["lastname"])
            ->setPassword($_POST["password"])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRoles("ROLE_USER");
        try {
            //Instance du Validator
            $validator = new Validator();
            $validator->validate($user);

        } catch(ValidationException $e) {
            return $e->getMessage();
        }

        //Hash du passwords
        $hash = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $user->setPassword($hash);
        //test si le media existe
        if (isset($_FILES["img"]) && !empty($_FILES["img"]["tmp_name"])) {
            try {
                //Import du fichier
                $media = $this->mediaService->addMedia($_FILES["img"]);
            } catch(\Exception $e) {
                echo $e->getMessage();
            }
        } 
        //Image par default
        else {
            $media = $this->mediaService->getDefaultImg();
        }
        
        $user->setMedia($media);

        //ajout en BDD
        $this->userRepository->save($user);
        return "Le compte a été ajouté en BDD";
    }

    /**
     * Méthode pour se connecter
     * @param array $post (super globale POST)
     * @return string $message de sortie
     */
    public function authenticate(array $post): string
    {
        //Test si les champs sont remplis
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            return "Veuillez remplir tous les champs du formulaire";
        }

        //Nettoyer les entrées utilisateurs
        Tools::sanitize_array($_POST);
        //Récupérer le compte user
        $user = $this->userRepository->findByEmail($_POST["email"]);

        //Test si le compte existe ou le mot de passe invalide
        if (!isset($user) || !password_verify($_POST["password"], $user->getPassword())) {
            return "Les informations de connexion sont invalides";
        }

        //Créer la session User
        $_SESSION["user"] = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "pseudo" => $user->getPseudo(),
            "roles" => $user->getRoles(),
            "img" => $user->getMedia()
        ];
        return "Connecté";
    }
}
