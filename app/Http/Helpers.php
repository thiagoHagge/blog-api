<?php
namespace App\Http;

use Illuminate\Support\Facades\Storage;

class Helpers
{
    public function cleanString($string) {
        $newString = preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/","/(Ñ)/"
            ),
            explode(" ", "a A e E i I o O u U n N"),
            $string
        );
        $newString = str_replace('ç', 'c', $newString);
        $newString = trim($newString);
        $newString = strtolower($newString);
        $newString = str_replace(' ', '-', $newString);
        return $newString;
    }

    public function createImageLink($image) {
        $path = Storage::disk('s3')->put('images', $image);
        $url = Storage::disk('s3')->url($path);
        return $url;
    }
}
