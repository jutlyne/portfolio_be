<?php
/**
 * This is the image handle trait.
 * using for photo interaction
 * php version 8.0.17
 *
 * @package App\Traits
 */

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * A trait that is used to upload and delete media files.
 */
trait MediaTrait
{
    /**
     * It takes a file, uploads it to the storage disk, and returns an array with the file's URL and
     * MIME type
     *
     * @param file|string $media The file that you want to upload.
     * @param string      $path  The path to the folder where the file will be stored.
     *
     * @return array array with the url and mime type of the uploaded file.
     */
    public function uploadMedia($media, string $path = null)
    {
        $mimeType = $media->getMimeType();
        @list(, $mimeType) = explode('/', $mimeType);

        $originalExt = $media->getClientOriginalExtension();
        $fullPath = $path . '/' . Str::uuid() . '.' . $originalExt;

        Storage::disk()->put($fullPath, file_get_contents($media));

        return [
            'url' => $fullPath,
            'mime_type' => $mimeType,
        ];
    }

    /**
     * It deletes the file from the storage disk
     *
     * @param path $path The path to the file you want to delete.
     *
     * @return return value is a boolean.
     */
    public function removeMedia($path)
    {
        return Storage::disk()->delete($path);
    }

    /**
     * It takes a base64 encoded image, decodes it, and saves it to the specified path
     *
     * @param string|null $thumbnail The base64 string of the image.
     * @param string|null $path      The path to the folder where the image will be saved.
     *
     * @return string The full path of the file.
     */
    public function uploadImagesBase64($thumbnail, string $path = null): string
    {
        @list($type, $base64Data) = explode(';', $thumbnail);
        @list(, $originalExt) = explode('/', $type);
        @list(, $fileData) = explode(',', $base64Data);

        $fileName = Str::uuid() . $originalExt;
        $fullPath = $path . '/' . $fileName;
        Storage::disk()->put($fullPath, base64_decode($fileData));

        return $fullPath;
    }
}
