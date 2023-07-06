<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ImageService
{
    public function updateImage($model, $request, $url, $methodType): void
    {
        $image = Image::make($request->file('image'));

        $path = storage_path($url);
        !is_dir($path) &&
        mkdir($path, 0777, true);

        if (!empty($model->image)) {
            $currentImage = $path.$model->image;

            if (file_exists($currentImage)) {
                unlink($currentImage);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $image->resize(320,240, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $name = time() . '.' . $extension;
        $image->save($path.$name);

        if ($methodType === 'store' && $model->user_id) {
            $model->user_id = Auth::user()->id;
        }

        $model->image = $name;

        $model->save();
    }
}
