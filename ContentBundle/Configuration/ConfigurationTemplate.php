<?php

namespace CAF\ContentBundle\Configuration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;


class ConfigurationTemplate
{

	public function __construct() {
	}

	public function getTemplates($ds)
	{	
		$finder = new Finder();
		$path = __DIR__;
		$pos = strrpos(substr($path,0,strlen($path)-2), $ds);
		$path = substr($path, 0, $pos);			
		$path .= $ds.'Resources'.$ds.'views'.$ds.'Templates'.$ds; 		
		$uploads_file = $finder->files()->in($path);
		foreach($uploads_file as $fichier) {
            $temp = substr($fichier, strrpos($fichier, $ds)+1);
            $temp = explode('_', $temp);
            $fichiers[$temp[0]] = $temp[0];
        }
        $fichiers = array_unique($fichiers);
        return $fichiers;
    }
}        