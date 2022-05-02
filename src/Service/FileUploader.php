<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;



class FileUploader
{
    private $uploadsPath;
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }
    public function uploadImage(UploadedFile $uploadedFile,$folder): string
    {
        $destination = $this->uploadsPath.$folder;
    
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        
        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }
    public function deleteImage(string $path)
    {
        $imagePath = $this->uploadsPath.$path;
        if(file_exists($imagePath)){
            unlink($imagePath);
        }
    }
}