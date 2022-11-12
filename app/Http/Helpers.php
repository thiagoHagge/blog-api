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
        $newString = preg_replace('/[^a-zA-Z0-9\s]/', '', $newString);
        $newString = str_replace('\n', '', $newString);
        $newString = str_replace('.', '', $newString);
        $newString = str_replace('\\', '', $newString);
        $newString = str_replace('/', '', $newString);
        $newString = str_replace('=', '', $newString);
        $newString = str_replace('?', '', $newString);
        $newString = str_replace('#', '', $newString);
        $newString = str_replace('ç', 'c', $newString);
        $newString = str_replace('-', ' ', $newString);
        $newString = str_replace('—', ' ', $newString);
        $newString = trim($newString);
        $newString = strtolower($newString);
        $newString = preg_replace('/\s+/', ' ', $newString);
        $newString = str_replace(' ', '-', $newString);
        return $newString;
    }

    public function createImageLink($image) {
        $filename = $image->store('', ['disk' => 'amei']);
        return "https://amei-ba.thiagohagge.com/images/$filename";
    }

    function getUrlTitle($url)
    {
        $url = explode('?' , $url)[0];
        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.83 Safari/537.36'));
        $output = curl_exec($ch);
        // get the code of request
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // FAIL
        if ($httpCode == 400) return $url;

        // SUCCEED!
        if ($httpCode == 200) {
            if (strlen($output) > 0) {
                $output = trim(preg_replace('/\s+/', ' ', $output)); // supports line breaks inside <title>
                preg_match("/\<title\>(.*)\<\/title\>/i", $output, $title); // ignore case
                return $title[1];
            }
        }
    }

}
