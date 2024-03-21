<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

abstract class BaseSeeder extends Seeder
{
    protected $faker;

    protected $config;

    public function __construct()
    {
        $this->faker = Faker::create();
        $this->config = config('settings.seeder');
    }

    protected function seedFile($fileName, $subDir, $visibility = 'public')
    {
        $seedOriginDir = resource_path('assets/test/images/');
        $filePath = $seedOriginDir . $fileName;

        if (File::exists($filePath)) {
            $file = File::get($filePath);

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);

            $fileName = Str::random(40) . '.' . $extension;

            $browserCache = config('settings.image-browser-cache');

            $storageFilePath = $subDir . '/' . $fileName;

            Storage::getDriver()->put($storageFilePath, (string) $file, [
                'visibility'            =>  $visibility,
                'Expires'               =>  gmdate('D, d M Y H:i:s', time() + $browserCache) . ' GMT',
                'CacheControl'          =>  $visibility . ', max-age=' . $browserCache,
                'ContentType'           =>  File::mimeType($filePath),
                'ContentDisposition'    =>  'inline; filename="' . $fileName . '"',
            ]);

            return $storageFilePath;
        }

        return null;
    }
}
