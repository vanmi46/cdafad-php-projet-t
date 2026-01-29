<?php

namespace App\Service;

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
            $mediaName  = $this->uploadService->uploadFile($files);
        } catch(UploadException $e) {
            throw new \Exception($e->getMessage());
        }

        $media = new Media($mediaName, $mediaName, new \DateTimeImmutable());

        $saved = $this->mediaRepository->save($media);
        if ($saved === null) {
            throw new \Exception("Erreur lors de l'enregistrement du média");
        }
        return $saved;
    }

    public function getDefaultImg(): ?Media 
    {
        return $this->mediaRepository->find(1);
    }
}
