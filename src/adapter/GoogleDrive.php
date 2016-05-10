<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 9:32 AM
 */

namespace Traktor\Bot\Adapter;


use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Traktor\Bot\TaskResponse;
use Traktor\Bot\TaskResult;

class GoogleDrive implements CloudStorage
{
    protected function getClient()
    {
        $client = new Google_Client();
        $client->setClientId(config('gdrive.clientid'));
        $client->setClientSecret(config('gdrive.secret'));
//        $client->setRedirectUri('<YOUR_REGISTERED_REDIRECT_URI>');
        $client->setScopes([Google_Service_Drive::DRIVE_FILE]);
        return $client;
    }

    protected function getDriveFile(string $filename): Google_Service_Drive_DriveFile
    {
        $file = new Google_Service_Drive_DriveFile();
        $file->setTitle($filename);
        $file->setDescription('A test image');
        $file->setMimeType('image/jpeg');
        return $file;
    }

    public function upload(string $filename): TaskResult
    {
        $result = new TaskResult();

        try {
            $client = $this->getClient();
            $file = $this->getDriveFile(pathinfo($filename, PATHINFO_BASENAME));
            $service = new Google_Service_Drive($client);
            $data = file_get_contents($filename);
            $service->files->insert($file, [
                'data' => $data,
                'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart'
            ]);
            $result->code = TaskResponse::SUCCESS;
        } catch (\Exception $e) {
            $result->code = TaskResponse::FAIL;
            $result->message = $e->getMessage();
        }
        
        return $result;
    }
}