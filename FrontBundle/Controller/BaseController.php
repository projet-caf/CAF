<?php

namespace CAF\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CAF\ContentBundle\Entity\Repository\UrlsContentRepository;

class BaseController extends Controller
{

    /**
     * @Route("/item/{lang}/{country}/{lang_dest}/{item_id}", name="translateItem", requirements={"lang" = "fr|en|de","lang_dest" = "fr|en|de"}, defaults={"country"="fr"})
     * @Template()
     */
    public function translationAction($lang,$country,$lang_dest,$item_id) {
        if($item_id != null) {
            $content_translation = $this->getDoctrine()
                                      ->getRepository('CAFContentBundle:ContentTranslation')
                                      ->find($item_id);

            $content = $content_translation->getContent();                     
            $translations = $content->getTranslations();
            foreach($translations as $t) {
                if($t->getLang()->getCode() == $lang_dest) {
                    $contentTranslationObj = $t;
                }
            }
            $url = $this->getDoctrine()
                            ->getRepository('CAFContentBundle:UrlsContent')
                            ->getUrlByContentTranslation($contentTranslationObj->getId());
            $url = current($url);
            return $this->redirect($this->generateUrl('front', array('lang' => $lang_dest, 'country' => $lang_dest, 'url' => $url)));
        }

        $url = 'accueil';
        return $this->redirect($this->generateUrl('front', array('lang' => $lang_dest, 'country' => $lang_dest, 'url' => $url)));
    }

    /**
     * @Route("/category/{lang}/{country}/{lang_dest}/{cat_id}", name="translateCat", requirements={"lang" = "fr|en|de","lang_dest" = "fr|en|de"}, defaults={"country"="fr"})
     * @Template()
     */
    public function translationCategoryAction($lang,$country,$lang_dest,$cat_id) {

        
        if($cat_id != null) {
            $categoryTranslationObj = $this->getDoctrine()
                                           ->getRepository('CAFContentBundle:CategoryTranslation')
                                           ->find($cat_id);
       
            if (is_object($categoryTranslationObj)) {
                $category = $categoryTranslationObj->getCategory();
                $translations = $category->getTranslations();
                foreach($translations as $t) {
                    if($t->getLang()->getCode() == $lang_dest) {
                        $categoryTranslationObj = $t;
                    }
                }
                $url = $categoryTranslationObj->getUrl();
                return $this->redirect($this->generateUrl('front', array('lang' => $lang_dest, 'country' => $lang_dest, 'url' => $url)));
            }
        }

        $url = 'accueil';
        return $this->redirect($this->generateUrl('front', array('lang' => $lang_dest, 'country' => $lang_dest, 'url' => $url)));
    }


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
            $item_id = $contentTranslationObj->getId();
            $template = $contentTranslationObj->getContent()->getIdContentTaxonomy()->getTemplate().'_item';
            $categories = $contentTranslationObj->getCategories();
            if (count($categories) == 1) 
                $cats = $categories[0]->getId();
            else {
                foreach($categories as $cat) {
                    $cats[] = $cat->getId();
                }
                $cats = implode(',', $cats);
            }

        } else if(is_object($categoryTranslationObj)) {
            $cat_id = $categoryTranslationObj->getId();
            $template = $categoryTranslationObj->getCategory()->getTemplate().'_category';
        }

        if ($template == '') {
            $template = 'error';
        }

    	$values = $this->haveBlocs($cats, $cat_id, $item_id, $lang);
        return array('lang' => $lang, 'article' => $contentTranslationObj, 'category' => $categoryTranslationObj, 'metas' => $metas_html, 'cat_id' => $cat_id, 'item_id' => $item_id,'display_menu' => $values['top'], 'cols' => $values['cols'], 'pos' => $values['pos'], 'country' => $country, 'path' => $path, 'template' => $template, 'url_cat' => $url_cat, '_format' => $_format, 'cats' => $cats);
    }



    private function getBlocs($position, $cats, $cat_id, $item_id, $lang)
    {
    	$repository = $this->getDoctrine()
                       ->getEntityManager()
                       ->getRepository('CAFBlocBundle:Bloc');
        if($item_id != null)
            $bloc_base = $repository->getBlocBaseItem($position, $cats, $item_id, $lang);
        else
            $bloc_base = $repository->getBlocBaseCategory($position, $cat_id, $lang);                              
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

    private function haveBlocs($cats, $cat_id, $item_id, $lang){
    	if($this->getBlocs('top', $cats, $cat_id, $item_id, $lang) || $this->getBlocsDefault('top')){
    		$values['top']=1;
    	}else{
    		$values['top']=0;
    	}
    	$pos=array('left', 'right');
    	$colonne = 1;
        $values['pos'] = '';
    	foreach ($pos as $key => $p) {
    		if($this->getBlocs($p, $cats, $cat_id, $item_id, $lang)  || $this->getBlocsDefault($p)){
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
