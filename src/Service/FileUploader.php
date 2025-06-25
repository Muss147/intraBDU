<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mime\MimeTypes;

class FileUploader
{
    private $targetDirectory;
    private $maxFileSize;
    private $fileSystem;
    
    public function __construct(string $targetDirectory, array $maxFileSize)
    {
        $this->targetDirectory = $targetDirectory;
        $this->maxFileSize = $maxFileSize; // ['images' => 2*1024*1024, 'videos' => 50*1024*1024, 'documents' => 10*1024*1024]
        $this->fileSystem = new Filesystem();
    }

    public function upload(UploadedFile $file): ?array
    {
        $mimeTypes = new MimeTypes();
        $mimeType = $file->getMimeType();
        $fileExtension = $mimeTypes->getExtensions($mimeType)[0] ?? $file->guessExtension();
        $fileType = $this->detectFileType($mimeType);
        
        if (!$fileType) {
            throw new \Exception("Type de fichier non pris en charge.");
        }

        if ($file->getSize() > $this->maxFileSize[$fileType]) {
            throw new FileException("Le fichier est trop volumineux. La taille maximale autorisée est de " . $this->maxFileSize[$fileType] / 1024 / 1024 . "MB.");
        }

        $fileName = uniqid() . '.' . $fileExtension;
        $filePath = '/uploads/' . $fileType . '/' . $fileName;
        // $filePath = $this->targetDirectory . '/' . $fileType . '/' . $fileName;

        try {
            $file->move($this->targetDirectory . '/' . $fileType, $fileName);
            return [
                'type' => $fileType,
                'filename' => $filePath
            ];
        } catch (FileException $e) {
            throw new FileException("Échec du téléchargement du fichier : " . $e->getMessage());
        }
    }

    public function delete(string $filePath): void
    {
        if ($this->fileSystem->exists($filePath)) {
            $this->fileSystem->remove($filePath);
        } else {
            throw new \Exception("Fichier non trouvé : $filePath");
        }
    }

    private function detectFileType(string $mimeType): ?string
    {
        $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $videoMimeTypes = ['video/mp4', 'video/mpeg', 'video/quicktime'];
        $documentMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (in_array($mimeType, $imageMimeTypes)) {
            return 'images';
        } elseif (in_array($mimeType, $videoMimeTypes)) {
            return 'videos';
        } elseif (in_array($mimeType, $documentMimeTypes)) {
            return 'documents';
        }

        return null;
    }
}