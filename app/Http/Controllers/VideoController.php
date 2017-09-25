<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2/9/2017
 * Time: 1:57 PM
 */

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use JP_COMMUNITY\Models\FileMaster;

class VideoController extends BaseController
{
    const
        DEFAULT_VIDEO_THUMB_SIZE = '480x480',// Maximum size value under `config.images`
        TYPE_VIDEO_FRAME = 'frame',
        ICON_THUMBNAIL_DEFAULT = '/assets/img/climbLogo.png';

    /**
     * @param $file
     * @param $uploadFolder
     */
    public function uploadMaster($file, $uploadFolder, $fileType = 'image') {
        // get file type
        $extensionType = $file->getClientMimeType();

        // set storage path to store the file (actual video)
        $destinationPath =  base_path('htdocs') .$uploadFolder;

        // get file extension
        $extension = $file->getClientOriginalExtension();


        $name = $file->getClientOriginalName();

        $fileName = strtolower(preg_replace("/[^\w]+/", "-",str_replace('.'.$extension,'',$name))). '_'.uniqid();

        $uploadStatus = $file->move($destinationPath, $fileName.'.'.$extension);

        $resFile = FileMaster::create([
            'user_id' => Auth::id(),
            'type' => $fileType,
            'name' => $fileName.'.'.$extension,
            'path' => $destinationPath,
            'extension' => $extension,
            'upload_by' => Auth::id()
        ]);

        if($uploadStatus)
        {
            if ($fileType == 'video' && $resFile) {
                // file type is video
                // set storage path to store the file (image generated for a given video)
                $thumbnailPath   = $destinationPath.'thumbnail/';
                $tmpPath = base_path('htdocs/uploads/tmp/');

                $videoPath       = $destinationPath.$fileName.'.'.$extension;

                // set thumbnail image name
                $thumbnailImage  = $fileName.".jpg";

                $thumbnailStatus = $this->makeThumbnail($videoPath, $thumbnailImage);

                if($thumbnailStatus)
                {

                    if (!file_exists($thumbnailPath)) {
                        mkdir($thumbnailPath, 0777, true);
                    }

                    copy($tmpPath.$thumbnailImage, $thumbnailPath.$thumbnailImage);
                    unlink($tmpPath.$thumbnailImage);
                }
                $resThumbnail = FileMaster::create([
                    'user_id' => Auth::id(),
                    'type' => 'image',
                    'name' => $thumbnailImage,
                    'path' => $thumbnailPath,
                    'extension' => 'jpg',
                    'upload_by' => Auth::id()
                ]);
                FileMaster::where('file_id', $resFile->file_id)
                ->update(
                    [
                    'thumbnail_id' => $resThumbnail->file_id
                ]);
            }
        }

        return [
            'video_id' => !empty($resFile) ? $resFile->file_id : null,
            'thumbnail_id' => !empty($resThumbnail) ? $resThumbnail->file_id : null,
        ];
    }

    /**
     * @param $videoPath
     * @param $thumbnailImage
     * @param array $options
     * @internal int $atSecond
     * @internal string $size Thumbnail size
     * @return bool
     */
    public function makeThumbnail($videoPath, $thumbnailImage, $options = []) {
        try {
            $pathTmp = base_path('/htdocs/uploads/tmp/');
            $atSeconds = !empty($options['atSecond']) ? $options['atSecond'] : 1;
            $thumbNailSize = !empty($options['size']) ? $options['size'] : self::DEFAULT_VIDEO_THUMB_SIZE;
            if (!file_exists($pathTmp)) {
                mkdir($pathTmp, 0777, true);
            }
            $thumbTmp = $pathTmp . $thumbnailImage;
            //Command; ffmpeg -ss %d -i %s -s %s -y -vframes 1 -an -f image2 %s
            $command = sprintf('ffmpeg -ss %d -i %s -s %s -y -vframes 1 -an -f image2 %s',
                $atSeconds,
                $videoPath,
                $thumbNailSize,
                $thumbTmp
            );

            // Exec command
            shell_exec($command);

            if (file_exists($thumbTmp)) {
                return true;
            }
        } catch (Exception $e) {
            echo 'Error make thumbnail';
        }
    }

}