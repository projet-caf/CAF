<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\BlocBundle\Entity\BlocMenuLeft
 *
 * @ORM\Table(name="blocmenuleft")
 * @ORM\Entity
 */
class BlocMenuLeft
{

	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\ManyToOne(targetEntity="Bloc")
    */
    private $bloc;

    /**
    * @ORM\OneToOne(targetEntity="CAF\MenuBundle\Entity\Menu")
    */
    private $menu;

    
    public function html($menus, $unique, $lang, $key, $size, $base_url, $cats, $cat_id, $item_id, $path=null){  
        $key++; 
        $html='';
        $cats = explode(',',$cats);
        if(!empty($menus)){            
            $html='<ul class="left_block">';
            foreach ($menus as $key_ent => $menu) {
                $class = ''; 
                if (isset($menu['category']) && in_array($menu['category'],$cats) && $menu['category'] != 0) {
                    $class = ' selected';
                } else if (isset($menu['category']) && $menu['category'] == $cat_id && $cat_id != 0 && $menu['category'] != 0) {
                    $class = ' selected';
                } else if (isset($menu['content']) && $menu['content'] == $item_id && $item_id != null && $menu['content'] != 0) {
                    $class = ' selected';
                } else {
                    $class = '';
                }
                $url = '#';
                
                if(isset($menu['urls_content'])){
                    $url = $menu['urls_content'];
                }

                $link = '';
                $start_url = substr($url,0,7);
                if($start_url == 'http://') {
                    $link = $url;
                } else {
                    $link = $base_url.$url;
                }   
                $html.='<li class="menu_lat'.$class.' lvl_'.$menu['level'].'"><a href="'.$link.'">'.$menu['title'].'</a></li>';
            }
            $html.='</ul>'; 
            if($key == $size){
                $html.='<div class="left_block_bottom"></div>';
            }else{
                $html.='<div class="left_block_bottom grey_bck"></div>';
            }
        }
       
        $this->html = $html;
        return true;
    }

    public function __construct(){
        $bloc = new Bloc();
        $this->setBloc($bloc);
    }
    public function getType(){
        return 'BlocMenuLeft';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set bloc
     *
     * @param CAF\BlocBundle\Entity\Bloc $bloc
     */
    public function setBloc(\CAF\BlocBundle\Entity\Bloc $bloc)
    {
        $this->bloc = $bloc;
    }

    /**
     * Get bloc
     *
     * @return CAF\BlocBundle\Entity\Bloc 
     */
    public function getBloc()
    {
        return $this->bloc;
    }

    /**
     * Set display_type
     *
     * @param string $displayType
     */
    public function setDisplayType($displayType)
    {
        $this->display_type = $displayType;
    }

    /**
     * Get display_type
     *
     * @return string 
     */
    public function getDisplayType()
    {
        return $this->display_type;
    }

    /**
     * Set menu
     *
     * @param CAF\MenuBundle\Entity\Menu $menu
     */
    public function setMenu(\CAF\MenuBundle\Entity\Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu
     *
     * @return CAF\MenuBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }
}