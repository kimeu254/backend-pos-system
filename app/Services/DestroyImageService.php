<?php

namespace App\Services;

class DestroyImageService
{
    public function destroyImage($model, $url): void
    {
        $path = storage_path($url);

        if (!empty($model->image)) {
            $currentImage = $path.$model->image;

            if (file_exists($currentImage)) {
                unlink($currentImage);
            }
        }

        $model->image = null;

        $model->save();
    }
}
