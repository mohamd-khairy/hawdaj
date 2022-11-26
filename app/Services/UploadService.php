<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class UploadService
{

    /**
     * @param $files
     * @param string $path
     * @param string $disk
     * @return array|false|mixed|string
     */
    public static function store($files, string $path = 'files', string $disk = 'public')
    {
        $items = is_array($files) ? $files : [$files];

        $paths = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                if (is_file($item)) {
                    $paths[] = Storage::disk($disk)->putFile($path, $item);

                } elseif (is_string($item) && is_base64($item)) {

                    $path .= '/' . \Str::random(10) . time() . '.jpg';

                    if (Storage::disk($disk)->put($path, base64_decode($item))) {
                        $paths[] = $path;
                    }
                }
            }
        }

        return $paths ? (count($paths) == 1 ? $paths[0] : $paths) : null;
    }

    /**
     * @param $files
     * @param string $disk
     * @return bool
     */
    public static function delete($files, string $disk = 'public'): bool
    {
        $items = is_array($files) ? $files : [$files];

        if (!empty($items)) {
            foreach ($items as $item) {
                Storage::disk($disk)->delete($item);
            }
        }

        return true;
    }
}