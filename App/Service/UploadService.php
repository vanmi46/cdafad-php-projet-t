<?php

namespace App\Service;

use App\Utils\Tools;
use App\Service\Exception\UploadException;

class UploadService
{
    /**
     * Attributs du service d'upload
     */
    private readonly string $uploadtarget;
    private readonly int $uploadSizeMax;
    private readonly string $uploadFormatWhiteList;

    public function __construct()
    {
        //Initialisation des attributs (depuis le fichier .env)
        $this->uploadtarget = $_ENV["UPLOAD_DIRECTORY"];
        $this->uploadSizeMax = (int) $_ENV["UPLOAD_SIZE_MAX"];
        $this->uploadFormatWhiteList = $_ENV["UPLOAD_FORMAT_WHITE_LIST"];
    }
    
    /**
     * Méthode pour uploader un fichier
     * @param array $files (super globale Files)
     * @param string $name (nom du fichier)
     * @return string Nom de l'image uploadée
     * @throws UploadException
     */
    public function uploadFile(array $files): string
    {
        //Test si le fichier est bien uplodé
        if ($this->isFileUploadCorrectly($files)) {
            throw new UploadException("Pas de fichier à importer");
        }

        //test de la taille
        if ($this->validateUploadSize($files)) {
            throw new UploadException("La taille du fichier est trop importante");
        }

        //Récupération de l'extension
        $ext = Tools::getFileExtension($files["name"]);

        //Test si le format du fichier est valide
        if (!$this->validateUploadFormat($ext)) {
            throw new UploadException("Le format " . $ext . " est invalide");
        }

        //rename files
        $newName =  $this->renameFile($ext);
        $uploadTmp = $files["tmp_name"];
        $uploadtarget = $this->uploadtarget . $newName;

        //move to Upload_directory
        move_uploaded_file($uploadTmp, $uploadtarget);
        return $newName;
    }

    /**
     * Méthode pour tester si l'image à bien été uploadée
     * @param array $files (données du fichier)
     * @return bool Vrai si le fichier a été uploadé correctement, faux sinon
     */
    private function isFileUploadCorrectly(array $files): bool
    {
        return !isset($files["tmp_name"]) || empty($files["tmp_name"]);
    }

    /**
     * Méthode pour valider la taille de l'upload
     * @param array $files (données du fichier)
     * @return bool Vrai si la taille est trop importante, faux sinon
     */
    private function validateUploadSize(array $files): bool
    {
        return $files["size"] > $this->uploadSizeMax;
    }

    /**
     * Méthode pour valider le format de l'upload
     * @param string $ext (extension du fichier)
     * @return bool Vrai si le format est valide, faux sinon
     */
    private function validateUploadFormat(string $ext): bool
    {
        return in_array($ext, json_decode($this->uploadFormatWhiteList));
    }

    /**
     * Méthode pour renommer le fichier
     * @param string $ext (extension du fichier)
     * @return string Nouveau nom du fichier
     */
    private function renameFile(string $ext): string
    {
        return uniqid() . "." . $ext;
    }
}
