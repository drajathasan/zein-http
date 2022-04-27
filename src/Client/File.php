<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-26 07:32:49
 * @modify date 2022-04-26 08:23:59
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Client;

use Exception;

trait File
{
    private string $filePath;
    private bool $downloaded;
    
    public static function download(string $url, string $savingDirectory, array $options = [])
    {
        $instance = static::getInstance();

        // Check directory first before downloading file
        if (!is_writable($savingDirectory)) throw new Exception("{$savingDirectory} is not writeable. Make it first.");
        
        // Set random Filepath
        $instance->filePath = $savingDirectory . DIRECTORY_SEPARATOR . 'downloaded-file-' . basename($url);

        // Sink file
        $instance->call('GET', $url, array_merge(['sink' => $instance->filePath], $options));

        // set download status 
        $instance->downloaded = file_exists($instance->filePath) && filesize($instance->filePath);

        return $instance;
    }

    public function isDownloaded()
    {
        return static::getInstance()->downloaded??null;
    }

    public function saveAs(string $newFilename)
    {
        if (!static::getInstance()->filePath) throw new Exception("Method {__METHOD__} is available after download method");

        // Define downloaded directory
        $directory = dirname(static::getInstance()->filePath);

        // Moving
        return rename(static::getInstance()->filePath, $directory . DIRECTORY_SEPARATOR . $newFilename);
    }
}
