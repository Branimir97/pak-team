<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    private $uploadsPath;
    private $coversPath;
    private $soldVehiclesPath;

    public function __construct(string $uploadsPath, string $coversPath, string $soldVehiclesPath)
    {
        $this->uploadsPath = $uploadsPath;
        $this->coversPath = $coversPath;
        $this->soldVehiclesPath = $soldVehiclesPath;
    }

    public function uploadVehicleImage(UploadedFile $uploadedFile): ?string
    {
        $destination = $this->uploadsPath;
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        if(!in_array($uploadedFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
            return null;
        }
        $newFileName = $originalFileName .'-'. uniqid() .'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFileName);
        return $newFileName;
    }

    public function uploadCoverImage(UploadedFile $uploadedFile): ?string
    {
        $destination = $this->coversPath;

        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        if(!in_array($uploadedFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
            return null;
        }
        $newFileName = $originalFileName .'-'. uniqid() .'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFileName);
        return $newFileName;
    }

    public function uploadSoldVehicleImage(UploadedFile $uploadedFile): ?string
    {
        $destination = $this->soldVehiclesPath;
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        if(!in_array($uploadedFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
            return null;
        }
        $newFileName = $originalFileName.'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFileName);
        return $newFileName;
    }
}