<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use \Intervention\Image\Encoders\AutoEncoder;

class AdminHelpers 
{
    /**
     * Store model image with thumbnail
     * @param $req
     * @param $imageFieldName
     * @param $dirName
     * @return string
     */
    public static function storeModelImage($req, $imageFieldName, $dirName) {
        $randomStr = Str::random(20);
        $ext = $req->file($imageFieldName)->getClientOriginalExtension();
        $path = $dirName . '/' . $randomStr . '.' . $ext;
        $pathSmall = $dirName . '/' . $randomStr . '-sm.' . $ext;

        // Compress and resize
        $smallImg = Image::read($req->file($imageFieldName))->scaleDown(500)->encode(new AutoEncoder(quality: 70));
        $originalImg = Image::read($req->file($imageFieldName))->encode(new AutoEncoder(quality: 70));

        // Store images
        $storageOriginal = Storage::disk('public')->put($path, $originalImg);
        $storeSmall = Storage::disk('public')->put($pathSmall, $smallImg);

        throw_if($storageOriginal == false || $storeSmall == false);

        return $path;
    }

    /**
     * Rmove model image with thumbnail
     * @param $path
     * @return bool
     */
    public static function removeModelImage($path) {
        if ($path == null || $path == '') return;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $pathSmall = Str::replaceLast('.'.$ext, '', $path) . '-sm.' . $ext;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        if (Storage::disk('public')->exists($pathSmall)) {
            Storage::disk('public')->delete($pathSmall);
        }

        return true;
    }

    /**
     * Convert PDF to JPG
     * @param $dirName
     * @param $filePath
     * @return string
     */
    public static function convertPDFtoJPG($dirName, $filePath) {
        $randomStr = Str::random(20);
        $path = storage_path('app/public/' . $dirName . '/' . $randomStr . '.jpg');
        $pathSmall = storage_path('app/public/' . $dirName . '/' . $randomStr . '-sm.jpg');

        $spatieImg = new \Spatie\PdfToImage\Pdf(Storage::disk('public')->path($filePath));
        $spatieImg->setCompressionQuality(70)->saveImage($path);

        // Store thumbnail
        $spatieImg->width(500)->setCompressionQuality(70)->saveImage($pathSmall);

        // return image path
        return $dirName . '/' . $randomStr . '.jpg';
    }
}