<?php

class cache
{
    public function consumeAPI($url, $expire = 0)
    {
        $filehash = md5($url).'.json';
        $filehash = STORAGE_PATH.'/'.$filehash;

        if ($expire == 0) {
            $expire = CACHE_JSON_EXPIRE;
        }
        if($expire >= 0) {
            if ($expire > 0 && file_exists($filehash) && time() - filemtime($filehash) < CACHE_JSON_EXPIRE) {

                return $this->loadCacheJson($filehash);
            }
        }

        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $response_decoded = json_decode($response, true);

            if ( $response_decoded !== null ) {
                if (is_array($response_decoded) && count($response_decoded) > 0) {
                    file_put_contents($filehash, $response);
                }
                return $response_decoded;
            } else {
                return $this->loadCacheJson($filehash, $url);
            }
        } catch (\Exception $e) {

            return $this->loadCacheJson($filehash, $url, $e);

        }
    }

    public function loadCacheJson($filehash, $url = '', $e = '')
    {
        if (file_exists($filehash)) {
            return json_decode(file_get_contents($filehash), true);
        } else {
            return null;
        }
    }
}
