<?php 
namespace Illuminate\Common;

class Download {

    /**
     * 
     * function common
     * @author Trần Công Tuệ <chanhduypq@gmail.com>
     * @param string $path
     * @param string $fileName
     */
    public static function download($response,$path, $fileName = null) {
        if (!is_string($fileName) || trim($fileName) == '') {
            $fileNameForDownload = '';
            $files = scandir($path, 0);
            foreach ($files as $file) {
                if ($file != '.' || $file != '..') {
                    $fileNameForDownload = $file;
                }
            }
        } else {
            $fileNameForDownload = $fileName;
        }
        $headers = array(
                          'Content-Type: application/octet-stream',
                        );
        
        return $response->download($path.$fileNameForDownload,$fileNameForDownload,$headers);        
    }


}
