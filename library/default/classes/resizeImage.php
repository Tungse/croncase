<?php

class library_default_classes_resizeImage extends library_default_classes_controller {

    public function  __construct($user, $fileInfo, $image) {

        $this->user = $user;
        $this->fileInfo = $fileInfo;
        $this->image = $image;
        
    }

    public function resizeImage($type, $input = NULL) {

        switch($type) {

            case 'avatar':
                $size['width'] = $size['widthDefault'] = 240;
                $size['height'] = $size['heightDefault'] = 160;
                $imageCreate = self::createImage();
                $size = self::getNewSize($size);
                $folder = 'avatar';
                break;
            case 'friend':
                $size['width']  = $size['widthDefault'] = 94;
                $size['height']  = $size['heightDefault'] = 72;
                $imageCreate = self::createImage();
                $size = self::getNewSize($size);
                $folder = 'avatar';
                break;
            case 'thumbnail':
                $size['width']  = $size['widthDefault'] = 56;
                $size['height']  = $size['heightDefault'] = 56;
                $imageCreate = self::createImage();
                $size = self::getNewSize($size);
                $folder = 'avatar';
                break;
            case 'eventImage':
                $size['width'] = $size['widthDefault'] = 200;
                $size['height'] = $size['heightDefault'] = 160;
                $imageCreate = self::createImage();
                $size = self::getNewSize($size);
                $folder = 'event/'.$input;
                break;
            case 'eventImageSmall':
                $size['width'] = $size['widthDefault'] = 94;
                $size['height'] = $size['heightDefault'] = 72;
                $imageCreate = self::createImage();
                $size = self::getNewSize($size);
                $folder = 'event/'.$input;
                break;
            case 'background':
                $size['width'] = $size['widthDefault'] = $this->fileInfo[0];
                $size['height'] = $size['heightDefault'] = $this->fileInfo[1];
                $imageCreate = self::createImage();
                $folder = 'home';
                break;

        }

        $newImage = imagecreatetruecolor($size['width'], $size['height']);
        imagecopyresampled($newImage, $imageCreate, 0, 0, 0, 0, $size['width'], $size['height'], $this->fileInfo[0], $this->fileInfo[1]);
        imagejpeg($newImage, 'data/users/'.$this->user['md5'].'/'.$folder.'/'.$type.'.jpg', 75);
        imagedestroy($newImage);
        chmod('data/users/'.$this->user['md5'].'/'.$folder.'/'.$type.'.jpg', 0755);

    }

    private function createImage() {

        switch($this->fileInfo['mime']) {

            case 'image/jpeg':
                $imageCreate = imagecreatefromjpeg($this->image);
                break;
            case 'image/gif':
                $imageCreate = imagecreatefromgif($this->image);
                break;
            case 'image/png':
                $imageCreate = imagecreatefrompng($this->image);
                break;
            default:
                return false;

        }

        return $imageCreate;

    }

    private function getNewSize($size) {

        if($this->fileInfo[0] > $size['width']) {

            $imageScale = $size['width']/$this->fileInfo[0];
            $size['height'] = $this->fileInfo[1]*$imageScale;

        } elseif($this->fileInfo[1] > $size['height']) {

            $imageScale = $size['height']/$this->fileInfo[1];
            $size['width'] = $imageScale*$this->fileInfo[0];

        }

        if($size['height'] > $size['heightDefault']) {

            $imageScale = $size['heightDefault']/$size['height'];
            $size['width'] = $imageScale*$size['width'];
            $size['height'] = $size['heightDefault'];

        }

        if($size['width'] > $size['widthDefault']) {

            $imageScale = $size['widthDefault']/$size['width'];
            $size['height'] = $imageScale*$size['height'];
            $size['width'] = $widthDefault;

        }

        return $size;

    }

}

?>