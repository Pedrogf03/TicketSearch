<?php
class CurlHelper {
    public static function fetchUrl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error al obtener el contenido de la URL: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }
}
?>