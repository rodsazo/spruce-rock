<?php

namespace App\Helpers;

class VimeoData
{
    public static function getVimeoData( $videoUrl ) {
        $oembedUrl = 'https://vimeo.com/api/oembed.json?url=' . urlencode($videoUrl);

        $json = @file_get_contents($oembedUrl);
        if ($json === false) {
            return null;
        }

        return json_decode($json);
    }
}
