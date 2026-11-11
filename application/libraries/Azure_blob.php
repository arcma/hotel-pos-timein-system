<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/vendor/autoload.php');

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

class Azure_blob
{
    protected $blobClient;

    public function __construct()
    {
        $CI = &get_instance();
        $CI->load->config('azureblob');

        // Get the connection string and container name from the config file
        $this->connectionString = $CI->config->item('azure_blob_connection_string');
        $this->containerName = $CI->config->item('azure_blob_container');
        $this->containerFolderName = $CI->config->item('azure_blob_container_folder');

        // Create a BlobRestProxy object with the connection string
        $this->blobClient = BlobRestProxy::createBlobService($this->connectionString);
    }

    public function uploadFile($fileName, $filePath, $oldFileName = false)
    {
        try {

            if ($oldFileName) {
                // Check if the file with the parameter value exists
                $existingBlob = $this->blobClient->getBlob(
                    $this->containerName . '/' . $this->containerFolderName,
                    $oldFileName
                );

                // Delete the existing blob if it exists
                if ($existingBlob) {
                    $this->blobClient->deleteBlob(
                        $this->containerName . '/' . $this->containerFolderName,
                        $oldFileName
                    );
                }
            }

            // Upload the file to Azure Blob Storage
            $handle = fopen($filePath, 'r');
            $this->blobClient->createBlockBlob(
                $this->containerName . '/' . $this->containerFolderName,
                $fileName,
                $handle
            );
            return true;
        } catch (ServiceException $e) {
            // Handle exceptions that occur during the request
            return false;
        } catch (InvalidArgumentTypeException $e) {
            // Handle exceptions that occur due to invalid arguments
            return false;
        }
    }
}
