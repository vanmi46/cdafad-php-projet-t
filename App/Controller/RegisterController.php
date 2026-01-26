<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Utils\Tools;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\EntityInterface;

class RegisterController extends AbstractController
{
    private UserRepository $userRepository;

    //Injection du UserRepository
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    //Méthode pour s'inscrire
    public function register(): mixed
    {
        $data = [];
        //Test si le formulaire est submit
        if ($this->isFormSubmitted($_POST,  "submit")) {
            //Test si les champs sont remplis
            if (!empty($_POST["pseudo"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm-password"])) {
                //test si les 2 mots de passe sont identiques
                if ($_POST["password"] == $_POST["confirm-password"]) {
                    //Nettoyage des données
                    Tools::sanitize_array($_POST);
                    //créer un objet User
                    $user = new User();
                    //Set des attributs
                    $user
                        ->setEmail($_POST["email"])
                        ->setPseudo($_POST["pseudo"])
                        ->setFirstname($_POST["firstname"])
                        ->setLastname($_POST["lastname"])
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setRoles("ROLE_USER");
                    //Test si le compte n'existe pas déja
                    if (!$this->userRepository->isUserExists($_POST["email"], $_POST["pseudo"])) {
                        //Hash du passwords
                        $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                        $user->setPassword($hash);

                        //ajout en BDD
                        $this->userRepository->save($user);
                        $data["msg"] = "Le compte a été ajouté en BDD";
                    } else 
                    {
                         $data["msg"] = "Le compte existe déjà en BDD";
                    }
                } 
                //Sinon les champs ne sont pas identiques
                else {
                    $data["msg"] = "Les mots de passe ne sont pas identiques";
                }
            } 
            //Sinon les champs ne sont pas remplis 
            else {
                $data["msg"] = "Veuillez remplir les champs du formulaire";
            }
        }
        return $this->render("register", "S'inscrire", $data);
    }

    //Méthode pour se connecter
    public function login(): mixed
    {
        return $this->render("login", "Se connecter");
    }

    //Méthode pour se connecter
    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
