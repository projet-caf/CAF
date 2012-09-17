<?php

namespace CAF\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\Menu;

/**
 * @Route("/common")
 */
class CommonController extends Controller
{
    
    /**
     * @Route("/header")
     * @Template()
     */
    public function headerAction($display_menu, $lang, $cat_id, $item_id, $path=null, $country = 'fr',$cats='')
    {
        $menus = $this->getDoctrine()
                ->getRepository('CAFMenuBundle:Menu')
                ->findBy(array('id_menu_taxonomy'=>'1'));

        return array('menus' => $menus, 'display_menu' => $display_menu, 'lang' => $lang, 'cat_id' => $cat_id, 'item_id' => $item_id, 'path' => $path, 'country' => $country, 'cats' => $cats);
    }


    /**
     * @Route("/banner")
     * @Template()
     */
    public function bannerAction($slider, $lang, $cat_id, $item_id, $display_menu=0,$cats='')
    {
        $menus = $this->getDoctrine()
                ->getRepository('CAFMenuBundle:Menu')
                ->findBy(array('id_menu_taxonomy'=>'1'));

        return array('menus' => $menus, 'slider' => $slider, 'lang' => $lang, 'cat_id' => $cat_id, 'item_id' => $item_id, 'display_menu' => $display_menu, 'cats' => $cats);
    }

    /**
     * @Route("/footer")
     * @Template()
     */
    public function footerAction($lang, $cat_id, $item_id,$cats='')
    {
        return array('lang' => $lang, 'cat_id' => $cat_id, 'item_id' => $item_id, 'cats' => $cats);
    }

    /**
     * @Route("/right")
     * @Template()
     */
    public function rightAction($lang, $cat_id, $item_id,$cats='')
    {
        return array('lang' => $lang, 'cat_id' => $cat_id, 'item_id' => $item_id, 'cats' => $cats);
    }

    /**
     * @Route("/left")
     * @Template()
     */
    public function leftAction($lang, $cat_id, $item_id, $path=null,$cats='')
    {
        return array('lang' => $lang, 'cat_id' => $cat_id, 'item_id' => $item_id, 'path' => $path, 'cats' => $cats);
    }
}
