<?php

namespace CAF\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\MediaBundle\Form\MediaType;

/**
 * @Route("/media")
 */
class DefaultController extends Controller
{
    
    /**
     * @Route("/media/{directory}", name="medias", defaults={"directory"="uploads"})
     * @Template()
     */
    public function indexAction(Request $request,$directory)
    {
    	$directory_separator = $this->container->getParameter("directory_separator");
        $directory_new = str_replace('_', $directory_separator,$directory);
    	$path = explode('_',$directory);
    	
    	$isRoot = false;
    	$prec = '';
    	if ($directory == 'uploads') {
    		$isRoot = true;
    	} else {
    		$prec = explode('_', $directory);
    		$prec = implode('_', array_slice($prec, 0, count($prec)-1));
    	}
    	$uploads_dir = Finder::create()
				    ->directories()
				    ->depth(0)
				    ->in($_SERVER['DOCUMENT_ROOT'].$request->getBasePath().$directory_separator.$directory_new);
		
		$uploads_file = Finder::create()
						->files()
						->depth(0)
						->in($_SERVER['DOCUMENT_ROOT'].$request->getBasePath().$directory_separator.$directory_new);
		$current_path = $directory;				    

		$directories = array();	    
		$fichiers = array();

        foreach ($uploads_dir as $dir) {
		    $directories[] = substr($dir, strrpos($dir, $directory_separator)+1);		
		}

		foreach($uploads_file as $fichier) {
			$fichiers[] = substr($fichier, strrpos($fichier, $directory_separator)+1);
		}

    	return array('directories' => $directories, 'fichiers' => $fichiers, 'url' => $directory.'_', 'url_asset' => $request->getBasePath().'/'.$directory_new, 'prec' => $prec, 'isRoot' => $isRoot, 'path' => $path, 'current' => $current_path);
    }

    /**
     * @Route("/newdirectory", name="new_directory")
     * @Template()
     */
    public function createDirectoryAction(Request $request)
    {
    	if ($request->getMethod() == 'POST') {
            $directory_separator = $this->container->getParameter("directory_separator");
    		$directory = $request->request->get('directory_new');
    		$current = $request->request->get('current');
    		$current = str_replace('_',  $directory_separator,$current);

    		$fs = new Filesystem();

    		$new_directory = $_SERVER['DOCUMENT_ROOT'].$request->getBasePath().'/'.$current.'/'.$directory;
    		if($fs->mkdir($new_directory)) {
    			$this->get('session')->setFlash('success', 'Nouveau contenu sauvegardé');
    			return $this->redirect($this->generateUrl('medias', array('directory' => str_replace('/','_',$current))));
    		}
    	}
    	return $this->redirect($this->generateUrl('medias'));

    }

    /**
     * @Route("/delete/{current}/{elem}", name="delete_media", defaults={"current"="uploads","elem"="test"})
     * @Template()
     */
    public function deleteAction(Request $request, $current, $elem)
    {
        $directory_separator = $this->container->getParameter("directory_separator");
    	$current = str_replace('_',  $directory_separator, $current);
    	$base = $_SERVER['DOCUMENT_ROOT'].$request->getBasePath().'/'.$current.'/';
    	$fs = new Filesystem();
    	$current_path = $base.$elem;
    	$fs->remove($current_path);
    	return $this->redirect($this->generateUrl('medias', array('directory' => str_replace('/','_',$current))));
    }

}
