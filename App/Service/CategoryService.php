<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Utils\Tools;
use Mithridatem\Validation\Validator;
use Mithridatem\Validation\Exception\ValidationException;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function saveCategory(array $post): array
    {
        $errors = [];
        if (empty($post["name"])) {
            $errors["name"] = "Le nom est obligatoire";
            return ["errors" => $errors];
        }

        Tools::sanitize_array($post);

        if ($this->categoryRepository->isCategoryExists($post["name"])) {
            return ["errors" => ["name" => "La categorie existe deja"]];
        }
        
        $category = new Category($post["name"]);
        $category->setCreatedAt(new \DateTimeImmutable());
        
        try {
            $this->validateCategory($category);
        } catch(ValidationException $ve) {
            return ["errors" => ["_form" => $ve->getMessage()]];
        }

        if ($this->categoryRepository->save($category) === null) {
            return ["errors" => ["_form" => "Erreur lors de l'ajout de la categorie"]];
        }

        return ["message" => "La categorie a ete ajoutee en BDD"];
    }

    public function getAllCategories(): array
    {
        $categories = $this->categoryRepository->findAll();
        return $categories ?? [];
    }

    /**
     * Methode pour valider une category
     * @return void
     * @throws ValidationException
     */
    private function validateCategory(Category $category): void
    {
        $validator = new Validator();
        $validator->validate($category);
    }
}
