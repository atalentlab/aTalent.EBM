<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasFiles
{
    /**
     * File upload for models
     *
     * @param string $fieldName
     * @param null $uploadDir
     * @param string $visibility
     * @return bool|string
     */
    public function uploadFile(string $fieldName, $uploadDir = null, $visibility = 'public')
    {
        if ($file = request()->file($fieldName)) {
            $fileName = $file->hashName();

            if (!pathinfo($fileName, PATHINFO_EXTENSION)) {
                if ($ext = $file->getClientOriginalExtension()) {
                    $fileName .= '.' . $ext;
                }
            }

            $uploadDir = $uploadDir ? $uploadDir : self::$uploadDir;

            $path = $uploadDir . '/' . $fileName;

            // set metadata
            $browserCache = config('settings.image-browser-cache');

            Storage::getDriver()->put($path, file_get_contents($file), [
                'visibility'            =>  $visibility,
                'Expires'               =>  gmdate('D, d M Y H:i:s', time() + $browserCache) . ' GMT',
                'CacheControl'          =>  $visibility . ', max-age=' . $browserCache,
                'ContentType'           =>  request()->file($fieldName)->getMimeType(),
                'ContentDisposition'    =>  'inline; filename="' . $fileName . '"',
            ]);

            if ($this->$fieldName && Storage::exists($this->$fieldName)) {
                Storage::delete($this->$fieldName); // out with the old
            }

            $this->$fieldName = $path; // in with the new
            $this->save();

            return $path;
        }

        return false;
    }

    protected function cleanUpFiles()
    {
        foreach ($this->files as $file) {
            if ($this->$file && Storage::exists($this->$file)) {
                Storage::delete($this->$file);
            }
        }
    }
}
