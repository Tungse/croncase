<?php

class library_default_classes_view {

    public function trimString($string, $lenght, $type, $uid) {
        
        if(strlen($string) > $lenght) {

            switch($type) {

                case 'aboutMe':
                    $height = 160;
                    $width = 400;
                    break;
                case 'status':
                    $height = 160;
                    $width = 400;
                    break;
                default:
                    $height = 300;
                    $width = 400;
                    break;
            }

            $string = substr($string, 0, $lenght).'...<a class="thickbox" href="content?action='.$type.'&uid='.$uid.'&height='.$height.'&width='.$width.'"><small><i> (more)</i></small></a>';

        }
        
        return $string;
        
    }

}

?>