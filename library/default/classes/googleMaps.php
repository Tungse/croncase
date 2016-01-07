<?php

class library_default_classes_googleMaps {

    private function getURL($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $geoData = curl_exec($ch);
        curl_close($ch);
        
        return $geoData;

    }

    public function getCoordinates($address){

        $address = str_replace(' ', '+', $address);
        $geoData = array('latitude' => 0, 'longtitude' => 0);

        $url = 'http://maps.google.com/maps/geo?q='.$address.'&output=xml&key=ABQIAAAAdoFicXw5I1U56NMO2c-jihQ6sVcNnPGQlep8BxinYLstvtOkdhSsFLmYC7gBkmVJN98Vn65CJ-4SQw';
        $geoData = self::getURL($url);
        if ($geoData != false){
            $xml = new SimpleXMLElement($geoData);
            $requestCode = $xml->Response->Status->code;
            if ($requestCode == 200){

                $coordinates = $xml->Response->Placemark->Point->coordinates;
                $coordinates = explode(',', $coordinates);

                if (count($coordinates) > 1){
                    $geoData = array('latitude' => $coordinates[1], 'longtitude' => $coordinates[0]);
                }

            }
        }

        return $geoData;
        
    }

}

?>