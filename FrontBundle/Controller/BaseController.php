<?php

namespace CAF\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\Menu;

class BaseController extends Controller
{
    /**
     * @Route("/{lang}/{country}/{url}.{_format}", name="front", requirements={"lang" = "fr|en|de", "url"=".+", "_format"="html"}, defaults={"country"="fr", "url"="accueil.html", "_format"="html"})
     * @Template()
     */
    public function indexAction($lang,$country,$url,$_format)
    {
        $path = $this->generateUrl('front', array('lang' => $lang, 'country' => $country,'url' => $url));

    	$contentTranslationObj = null;
    	$categoryTranslationObj = null;
    	$metas_html = '';
        $metasvalues = array();
        $item_id = null;
        $cat_id = null;
        $cats = null;

		$contentTranslationObj = $this->getDoctrine()
									  ->getRepository('CAFContentBundle:ContentTranslation')
									  ->findByUrl($url);
        $contentTranslationObj = current($contentTranslationObj);
        if(is_object($contentTranslationObj)) {
		  $metasvalues = $contentTranslationObj->getMetasvalue();
		}

        if(is_object($contentTranslationObj)) {
            $url_cat = substr($url, 0,strrpos($url, '/'));
        } else {
            $url_cat = $url;
        }    

        $categoryTranslationObj = $this->getDoctrine()
                                      ->getRepository('CAFContentBundle:CategoryTranslation')
                                      ->findByUrl($url_cat);
        $categoryTranslationObj = current($categoryTranslationObj);

        if(is_object($categoryTranslationObj)) {
            $metasvalues = $categoryTranslationObj->getMetasvalue();
        }

        if (is_object($metasvalues)) {
            foreach($metasvalues as $metavalue) {
                $balise = $metavalue->getMeta()->getBalise();
                $value = $metavalue->getValue();
                if($value != '' && $balise != 'text')
                    $metas_html .= str_replace('#1', $value, $balise);
            }
        }
        $template = '';

        if(is_object($contentTranslationObj)) {
            $item_id = $contentTranslationObj->getContent()->getId();
            $template = $contentTranslationObj->getContent()->getIdContentTaxonomy()->getTemplate().'_item';
            $categories = $contentTranslationObj->getCategories();
            if (count($categories) == 1) 
                $cats = $categories[0]->getCategory()->getId();
            else {
                foreach($categories as $cat) {
                    $cats[] = $cat->getCategory()->getId();
                }
                $cats = implode(',', $cats);
            }

        } else if(is_object($categoryTranslationObj)) {
            $cat_id = $categoryTranslationObj->getCategory()->getId();
            $template = $categoryTranslationObj->getCategory()->getTemplate().'_category';
        } 

    	$values = $this->haveBlocs($cats, $cat_id, $item_id);
        return array('lang' => $lang, 'article' => $contentTranslationObj, 'category' => $categoryTranslationObj, 'metas' => $metas_html, 'cat_id' => $cat_id, 'item_id' => $item_id,'display_menu' => $values['top'], 'cols' => $values['cols'], 'pos' => $values['pos'], 'country' => $country, 'path' => $path, 'template' => $template, 'url_cat' => $url_cat, '_format' => $_format, 'cats' => $cats);
    }

    private function getBlocs($position, $cats, $cat_id, $item_id)
    {
    	$repository = $this->getDoctrine()
                       ->getEntityManager()
                       ->getRepository('CAFBlocBundle:Bloc');
        if($item_id != null)
            $bloc_base = $repository->getBlocBaseItem($position, $cats, $item_id);
        else
            $bloc_base = $repository->getBlocBaseCategory($position, $cat_id);                              
		if(!$bloc_base) {
			return false;
		}
		return true;
    }

    private function getBlocsDefault($position)
    {
        $repository = $this->getDoctrine()
                       ->getEntityManager()
                       ->getRepository('CAFBlocBundle:Bloc');            
        $bloc_base = $repository->getBlocBaseDefault($position);
        if(!$bloc_base) {
            return false;
        }
        return true;
    }

    private function haveBlocs($cats, $cat_id, $item_id){
    	if($this->getBlocs('top', $cats, $cat_id, $item_id) || $this->getBlocsDefault('top')){
    		$values['top']=1;
    	}else{
    		$values['top']=0;
    	}
    	$pos=array('left', 'right');
    	$colonne = 1;
        $values['pos'] = '';
    	foreach ($pos as $key => $p) {
    		if($this->getBlocs($p, $cats, $cat_id, $item_id)  || $this->getBlocsDefault($p)){
                $values['pos'] = $p;
    			$colonne++;
    		}
    	}
    	switch ($colonne) {
    		case 1:
    			$values['cols'] = 3;
    			break;
    		case 2:
    			$values['cols'] = 2;
    			break;
    		case 3:
    			$values['cols'] = 1;
    			break;
    	}

    	return $values;
    }

}
