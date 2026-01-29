<?php

namespace App\Service;

use App\Utils\Tools;
use App\Entity\Entity;
use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Service\UploadService;
use App\Service\Exception\UploadException;

class MediaService
{
    private MediaRepository $mediaRepository;
    private UploadService $uploadService;

    public function __construct()
    {
        $this->mediaRepository = new MediaRepository();
        $this->uploadService = new UploadService();
    }

    public function addMedia(array $files): Media 
    {
        try {
            //uploader le media
            $mediaName  = $this->uploadService->uploadFile($files);
        } catch(UploadException $e) {
            throw new \Exception($e->getMessage());
        }

        //Créer un objet Media
        $media = new Media($mediaName, $mediaName, new \DateTimeImmutable());

        //Ajouter en BDD le media
        return $this->mediaRepository->save($media);
    }

    public function getDefaultImg(): ?Media 
    {
        return $this->mediaRepository->find(1);
    }
}
