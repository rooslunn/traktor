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
use Traktor\Bot\Exception\MkdirFailed;
use Traktor\Bot\TaskResponse;
use Traktor\Bot\TaskResult;

class GoogleDrive implements CloudStorage
{
    protected function auth(Google_Client $client)
    {
        $credentialsPath =  expandHomeDirectory(config('gdrive.credentials.path'));
        $clientSecretPath = expandHomeDirectory(config('gdrive.client_secret_path'));
        $redirectUri = config('gdrive.redirect_uri');

        $client->setApplicationName('Traktor Bot');
        $client->setAuthConfigFile($clientSecretPath);
        $client->setRedirectUri($redirectUri);
        $client->setScopes([Google_Service_Drive::DRIVE_FILE]);

        if (file_exists($credentialsPath)) {
            $accessToken = file_get_contents($credentialsPath);
        } else {
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            $accessToken = $client->authenticate($authCode);

            $credentialsDir = dirname($credentialsPath);
            if (!@mkdir($credentialsDir, 0700, true) && !is_dir($credentialsDir)) {
                throw new MkdirFailed($credentialsDir);
            }

            file_put_contents($credentialsPath, $accessToken);
        }

        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, $client->getAccessToken());
        }
    }

    protected function getClient(): Google_Client
    {
        $client = new Google_Client();
        $this->auth($client);
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

    protected function insertFile(string $filename, Google_Client $client, Google_Service_Drive_DriveFile $file)
    {
        $service = new Google_Service_Drive($client);
        $data = file_get_contents($filename);
        $service->files->insert($file, [
            'data' => $data,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart'
        ]);
    }

    public function upload(string $filename): TaskResult
    {
        $result = new TaskResult();

        try {
            $client = $this->getClient();
            $driveFile = $this->getDriveFile(pathinfo($filename, PATHINFO_BASENAME));
            $this->insertFile($filename, $client, $driveFile);
            $result->code = TaskResponse::SUCCESS;
        } catch (\Exception $e) {
            $result->code = TaskResponse::FAIL;
            $result->message = $e->getMessage();
        }
        
        return $result;
    }
}