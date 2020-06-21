<?php 
namespace App\CustomHelpers;

use Illuminate\Support\Facades\Storage;

class registrationClass{

    public static function extFromBase64($image_64){
        return explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
    }

    public static function MoveImage($image_64, $img_Name, $path){

        $extension = registrationClass::extFromBase64($image_64);   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1); 

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64); 
        $image = str_replace(' ', '+', $image); 
        $imageName = $img_Name.'.'.$extension;
        Storage::disk('public_img')->put($path.'/'.$img_Name, base64_decode($image));
    }
}