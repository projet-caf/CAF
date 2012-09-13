<?php

namespace CAF\ContentBundle\Configuration;



class ConfigurationUpload
{

    public function getUploadRootDir($path_upload,$country,$lang,$taxonomy)
    {
        // the absolute directory path where uploaded documents should be saved       
        return $path_upload.$taxonomy.'/'.$country.'/'.$lang;
    }

    public function getUploadDir($country,$lang,$taxonomy)
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/'.$taxonomy.'/'.$country.'/'.$lang;
    }

    
}