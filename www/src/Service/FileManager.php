<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $sharedDirectory;

    public function __construct($sharedDirectory)
    {
        $this->sharedDirectory = $sharedDirectory;
    }

    public function upload(UploadedFile $file, string $name = '', string $subdir = '', bool $public = false): String
    {
        if ($name === '') {
            $name = $file->getBasename();
        }

        $fileName = $name;

        if ($public === false) {
            $dirPath = $this->sharedDirectory.'private/'. $subdir;
        } else {
            $dirPath = $this->sharedDirectory.'public/' . $subdir;
        }

        $fileExist = file_exists($dirPath.'/'.$fileName);
        if($fileExist === true){
            while(file_exists($dirPath.'/'.$fileName)) {
                // création d'un détrompeur unique
                $key = uniqid();
                $fileName = $name.'_'.$key;
            }
        }else{
            $fileName = $name.'_'. uniqid();
        }
        if (file_exists($dirPath) === false) {
            mkdir($dirPath, 644, true);
        }
        $fileName = $fileName.'.'.$file->guessExtension();

        $file->move($dirPath, $fileName);
        return $fileName;
    }

    public function download(string $name, string $subdir = ''): BinaryFileResponse
    {
        $dirPath = $this->sharedDirectory.'/private/'.$subdir;
        
        if (!file_exists($dirPath.'/'.$name)) {
            return new BinaryFileResponse('', 404);
        }

        $response = new BinaryFileResponse($dirPath.'/'.$name);
        return $response;
    }
}