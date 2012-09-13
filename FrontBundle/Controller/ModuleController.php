<?php

namespace CAF\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use CAF\AdminBundle\Entity\Bloc;

use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;

class ModuleController extends Controller
{
    
    /**
     * @Route("/bloc/generate", name="generatebloc")
     */
    public function generateBlocAction(Request $request, $position, $unique, $lang, $cat_id, $item_id, $country='fr', $path=null, $display_menu=0, $cats = '')
    {
        $repository = $this->getDoctrine()
                       ->getEntityManager()
                       ->getRepository('CAFBlocBundle:Bloc');
        $path_web = $request->getBasePath();
        $base_url = $this->container->get('router')->getContext()->getBaseUrl();
        $base_url .= '/'.$lang.'/'.$country.'/';
        if($item_id != null)
		    $bloc_base = $repository->getBlocBaseItem($position, $cats, $item_id);
        else
            $bloc_base = $repository->getBlocBaseCategory($position, $cat_id);  

        //if (!$bloc_base) {
            $bloc_temp = $repository->getBlocBaseDefault($position);
            $bloc_base = array_merge($bloc_base,$bloc_temp);
        //}


        if (!$bloc_base) {
            $module = null;
        }else{   
            if($unique){  	
                $params = json_decode($bloc_base[0]->getParams());
                $bloc = $this->getBloc($params);
                $module = $this->displayBloc($position,$display_menu, $path, $path_web, $bloc, $unique, $lang, 1, 1, $base_url,$cats,$cat_id,$item_id);
            }else{   
                foreach ($bloc_base as $key => $bloc_b) {   
                    $params = json_decode($bloc_b->getParams());
                    $bloc = $this->getBloc($params);
                    $module[] = $this->displayBloc($position,$display_menu, $path, $path_web, $bloc, $unique, $lang, count($bloc_base), $key, $base_url,$cats,$cat_id,$item_id);
                }            
            }
        }
        $html=$this->htmlpos($position);

        return $this->render('CAFFrontBundle:Modules:module.html.twig', array('module' => $module, 'lang' => $lang, 'unique' => $unique, 'render' => true, 'html' => $html));
        
    }

    public function getBloc($params){
        $repository = $this->getDoctrine()
                       ->getEntityManager()
                       ->getRepository('CAFBlocBundle:'.$params->bloc_type);
        return $repository->findOneBy(array('id' => $params->bloc_id));
    }

    public function displayBloc($position, $display_menu, $path, $path_web, $bloc, $unique, $lang, $size, $key, $base_url, $cats, $cat_id, $item_id){

        switch ($bloc->getType()) {
            case 'BlocMenu':
                return $this->displayBlocMenu($path_web, $bloc, $unique, $lang, $base_url,$cats,$cat_id,$item_id);
                break;
            case 'BlocMenuLeft':
                return $this->displayBlocMenuLeft($bloc, $unique, $lang, $key, $size, $base_url,$cats, $cat_id, $item_id, $path);
                break;
            case 'BlocActu':
                return $this->displayBlocActu($path_web, $bloc, $unique, $lang, $base_url);
                break;
            case 'BlocHtml':
                return $this->displayBlocHtml($position, $bloc, $lang, $key, $size);
                break;
            case 'BlocBannerSlide':
                return $this->displayBlocBannerSlide($bloc, $path_web, $display_menu);
                break;
            case 'BlocBanner':
                return $this->displayBlocBanner($bloc, $path_web, $display_menu);
                break;
            case 'BlocBannerRight':
                return $this->displayBlocBannerRight($bloc, $path_web);
                break;
            default:
                return null;
                break;
        }
    }

    public function displayBlocMenu($path_web,$bloc, $unique, $lang, $base_url, $cats, $cat_id, $item_id){
        $menu_tax = $bloc->getMenu();

        $needle = $this->getDoctrine()
               ->getEntityManager()
               ->getRepository('CAFMenuBundle:Menu')->getTreePublished($menu_tax->getId());
        if($lang != 'fr') {
            $needle->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            );
        }    
        $locales = $this->container->getParameter('locale_lang');              
        $needle->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locales[$lang]);
        $needle = $needle->getArrayResult();
        $module = $bloc->html($path_web, $needle, $unique, $lang, $base_url, $cats, $cat_id, $item_id);
        return $bloc->html;
    }

    public function displayBlocMenuLeft($bloc, $unique, $lang, $key, $size, $base_url, $cats, $cat_id, $item_id, $path){
        $entry = $bloc->getMenu();
        $er = $this->getDoctrine()
               ->getEntityManager()
               ->getRepository('CAFMenuBundle:Menu');
        $needle = $er->getTreePublishedByMenuId($entry->getLeft(), $entry->getRight(), $entry->getRoot(), $entry->getid());
        if($lang != 'fr') {
            $needle->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            );    
        }
        

        $locales = $this->container->getParameter('locale_lang');              

        $needle->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locales[$lang]);
        $needle = $needle->getArrayResult();
        $module = $bloc->html($needle, $unique, $lang, $key, $size, $base_url, $cats, $cat_id, $item_id, $path);
        return $bloc->html;
    }

    public function displayBlocBannerSlide($bloc, $path_web, $display_menu){
        $module = $bloc->html($bloc, $path_web, $display_menu);
        return $bloc->html;
    }

    public function displayBlocBanner($bloc, $path_web, $display_menu){
        $module = $bloc->html($path_web, $display_menu);
        return $bloc->html;
    }

    public function displayBlocBannerRight($bloc, $path_web){
        $module = $bloc->html($path_web);
        return $bloc->html;
    }

    public function displayBlocActu($path_web, $bloc, $unique, $lang, $base_url){
        $limit_value = $bloc->getLimitValue();
        $categorie = $bloc->getCategory();
        $lang_id = $this->getDoctrine()
                        ->getRepository('CAFAdminBundle:Language')
                        ->findBy(array('code' => $lang));

        $cat_translation_id = $this->getDoctrine()
                                ->getRepository('CAFContentBundle:Category')
                                ->getCategoryTranslation($categorie->getId(),$lang_id);
        $cat_translation_id = current($cat_translation_id);                                
        //var_dump($cat_translation); die();                                
                                
        $needle = $this->getDoctrine()
               ->getEntityManager()
               ->getRepository('CAFContentBundle:Content')->getContents($limit_value,$cat_translation_id,$lang);

        $module = $bloc->html($path_web, $needle, $unique, $lang, $base_url);
        return $module;
    }

    public function displayBlocHtml($position, $bloc, $lang, $key, $size){
        $module = $bloc->html($position, $bloc, $lang, $key, $size);
        return $module;
    }

    public function htmlpos($position){
        switch ($position) {
            case 'left':
                    $array['start']='<div class="left_block_side">';
                    $array['end']='</div>';
                    return $array;
                break;
            case 'right':
                    $array['start']='<div class="right_block">';
                    $array['end']='</div>';
                    return $array;
                break;
            default:
                    return null;
                break;
        }
    }

}
