<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Quizz;
use App\Entity\User;
use App\Utils\Tools;
use App\Repository\QuizzRepository;
use Mithridatem\Validation\Validator;
use Mithridatem\Validation\Exception\ValidationException;

class QuizzService
{
    private QuizzRepository $quizzRepository;

    public function __construct()
    {
        $this->quizzRepository = new QuizzRepository();
    }

    /**
     * Methode pour ajouter un quizz en BDD (logique metier)
     * @param array $post (super globale $_POST)
     * @return array resultat (message + erreurs)
     */
    public function addQuizz(array $post): array
    {
        $errors = [];
        if (empty($post["title"])) {
            $errors["title"] = "Le titre est obligatoire";
        }
        if (empty($post["description"])) {
            $errors["description"] = "La description est obligatoire";
        }
        if (empty($post["categories"]) || !is_array($post["categories"])) {
            $errors["categories"] = "Veuillez selectionner au moins une categorie";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        if (!isset($_SESSION["user"])) {
            return ["errors" => ["_form" => "L'utilisateur n'est pas connecte"]];
        }

        Tools::sanitize_array($post);
        
        $quizz = $this->createQuizz($post);

        try {
            $validator = new Validator();
            $validator->validate($quizz);
        } catch(ValidationException $e) {
            return ["errors" => ["_form" => $e->getMessage()]];
        }

        $saved = $this->quizzRepository->save($quizz);
        if ($saved === null) {
            return ["errors" => ["_form" => "Erreur lors de l'ajout du quizz"]];
        }

        return ["message" => "Le quizz " . $quizz->getTitle() . " a ete ajoute en BDD"];
    }

    /**
     * Methode pour setter le tableau de Category au Quizz
     * @param Quizz $quizz
     * @param array $categories tableau id Category
     * @return Quizz $quizz
     */
    private function createCategories(Quizz $quizz, array $categories): Quizz
    {
        foreach ($categories as $value) {
            $cat = new Category();
            $cat->setId($value);
            $quizz->addCategory($cat);
        }

        return $quizz;
    }

    /**
     * Methode pour hydrater un Quizz
     * @param array $post (super globale POST)
     * @return Quizz $quizz objet Quizz hydrate
     */
    private function createQuizz(array $post): Quizz
    {
        $quizz = new Quizz();
        $quizz
            ->setTitle($post["title"])
            ->setDescription($post["description"])
            ->setCreatedAt(new \DateTimeImmutable());
        $quizz = $this->createCategories($quizz, $post["categories"] ?? []);
        $author = new User();
        $author->setId($_SESSION["user"]["id"]);
        $quizz->setAuthor($author);

        return $quizz;
    }
}
