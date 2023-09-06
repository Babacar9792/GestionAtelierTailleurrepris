<?php

namespace App\Trait;

trait UploadImage{

  public function  uploadImage($photo)
    {
         $fileName = time() . '.'.$photo.extension();
            $photo->storeAs('public/image', $fileName);
    }

}
