<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-26 12:08:30
 * @modify date 2022-04-26 12:44:18
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Response;

trait File
{
    public static function download(string $filePath)
    {
        self::setHeader([
            ['Content-Description', 'File Transfer'],
            ['Content-Disposition', 'attachment; filename="'.basename($filePath).'"'],
            ['Content-Type', mime_content_type($filePath)],
        // Send content
        ])->setContent(file_get_contents($filePath))->send();
    }  
    
    public static function stream(string $filePath)
    {
        self::setHeader([
            ['Content-Description', 'File Transfer'],
            ['Content-Disposition', 'inline; filename="'.basename($filePath).'"'],
            ['Content-Transfer-Encoding', 'binary'],
            ['Accept-Ranges', 'bytes'],
            ['Content-Type', mime_content_type($filePath)],
        // Send content
        ])->setContent(file_get_contents($filePath))->send();
    }
}
