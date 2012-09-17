<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\BlocBundle\Entity\BlocMenu
 *
 * @ORM\Table(name="blocmenu")
 * @ORM\Entity
 */
class BlocMenu
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
    * @ORM\OneToOne(targetEntity="CAF\MenuBundle\Entity\MenuTaxonomy")
    */
    private $menu;

    /**
     * @var string $display_type
     *
     * @ORM\Column(name="display_type", type="string", length=255)
     */
    private $display_type;

    public function __construct(){
        $bloc = new Bloc();
        $this->setBloc($bloc);
    }

    public function html($path_web, $menus, $unique, $lang, $base_url, $cat_id, $item_id, $options=null){
        $html = '';
        //var_dump($menus[0]);die();
        switch ($this->display_type) {
            case 'banner':
                if(!empty($menus)) {
                    //var_dump($menus);die();
                    $html ='<div class="ban_navi">';
                    $html .= '<ul><li class="elem"></li>';
                    foreach ($menus as $key_ent => $entry) {
                        if( $entry['level'] == 0){
                            if (strpos($entry['title'], '%%')){                          
                                $title = explode('%%', $entry['title']);
                                $title = $title[0].'<br /><span class="bold">'.$title[1].'</span>';
                            }else{
                                $title = $entry['title'];
                            }
                            $class = $key_ent == 1 ? 'first':'';
                            $url = '';
                            if(isset($entry['urls_content'])){
                                $url = $entry['urls_content'];
                            }
                            if ($url == '') {
                                $url = $entry['urls'];
                            }
                            $link = '';
                            $start_url = substr($url,0,7);
                            if($start_url == 'http://') {
                                $link = $url;
                            } else {
                                $link = $base_url.$url;
                            }   
                            $html .= '<li class="'.$class.'"><a href="'.$link.'" title=""><span class="menu_name">'.$title.'</span></a>';
                        }
                    }
                    $html.='</ul>';             
                    $html.='</div>';
                }
                break;
            case 'footer':
                if(!empty($menus)) {
                    $html ='<div class="bottom_navi">';
                    $html .= '<ul>';
                    foreach ($menus as $key_ent => $entry) {
                        if( $entry['level'] == 0){
                            if (strpos($entry['title'], '%%')){                          
                                $title = explode('%%', $entry['title']);
                                $title = $title[0].'<br /><span class="bold">'.$title[1].'</span>';
                            }else{
                                $title = $entry['title'];
                            }
                            $class = $key_ent == 0 ? 'first':'';
                            $url = '';
                            if(isset($entry['url_content'])){
                                $url = $entry['url_content'];
                            }
                            if ($url == '') {
                                $url = $entry['urls'];
                            }
                            $link = '';
                            $start_url = substr($url,0,7);
                            if($start_url == 'http://') {
                                $link = $url;
                            } else {
                                $link = $base_url.$url;
                            }   
                            $html .= '<li><a href="'.$link.'" class="'.$class.'"><span class="menu_name">'.$title.'</span></a>';
                        }
                    }
                    $html.='</ul>';             
                    $html.='</div>';
                }
                break;
            case 'header':
                $level = 0;
                $menu_pictos = array();
                if(!empty($menus)) {
                    $html ='<div class="top_navi padding10">';
                    $html .= '<ul><li><a href="'.substr($base_url,0,strlen($base_url)-1).'" title="" class="first"><img src="'.$path_web.'/bundles/caffront/images/home.png" ></a>';
                    foreach ($menus as $key_ent => $entry) {
                        if($entry['menu_picto']) {
                            $menu_pictos[] = $entry;
                        } else {                    
                            if($entry['level'] == $level){
                                $html.='</li>';

                                $html .='<li class="separator"><span></span></li>';

                            } else if($entry['level'] > $level){
                                $level = $entry['level'];
                                $class = ' lvl_'.$level;
                                if($entry['level'] == 1) {
                                    $html.='<div class="submenu">';
                                }
                                $html.='<ul class="child '.$class.'">';
                            }
                            else if($entry['level'] < $level){
                                if($entry['level'] == 2) {
                                    if($level - $entry['level']==2) {
                                        $html .= '</li></ul></li></ul>';
                                        $html .=  $this->displayMenuPicto($menu_pictos, $base_url);
                                        $menu_pictos = array();
                                        $html .= '</div></li>';
                                    } 
                                } else if($level - $entry['level']==1 && $entry['level']==0) {
                                    $html .= '</li></ul>';
                                    $html .=  $this->displayMenuPicto($menu_pictos, $base_url);
                                    $menu_pictos = array();
                                    $html .= '</div></li>';
                                } else {
                                    $html.= str_repeat('</li></ul></li>', $level - $entry['level']);
                                }
                                $level = $entry['level'];

                                $html .='<li class="separator"><span></span></li>';
                            }       
                            if (strpos($entry['title'], '%%')){                          
                                $title = explode('%%', $entry['title']);
                                $title = $title[0].'<br /><span class="bold">'.$title[1].'</span>';
                            }else{
                                $title = $entry['title'];
                            }

                            $class = ($entry['level'] == 0) ? "parent " : "";
                            $class .= "lvl_".$entry['level'];
                            $url = '';
                                 
                            if(isset($entry['urls_content'])){
                                $url = $entry['urls_content'];
                            }

                            if ($url == '') {
                                $url = $entry['urls'];
                            }

                            $link = '';
                            $start_url = substr($url,0,7);
                            if($start_url == 'http://') {
                                $link = $url;
                            } else {
                                $link = $base_url.$url;
                            }   
                            
                            $html .= '<li class="'.$class.'"><a href="'.$link.'" title=""><span class="menu_name">'.$title.'</span></a>';

                            if($key_ent == count($menus)-1){
                                $html.='</li>'.str_repeat('</ul></div></li>', $level);

                            }
                        }
                    }
                    $html.='</ul>';             
                    $html.='</div>';
                }
                break;
        }
       
        $this->html = $html;
        return true;
    }

    public function getType(){
        return 'BlocMenu';
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
     * @param CAF\MenuBundle\Entity\MenuTaxonomy $menu
     */
    public function setMenu(\CAF\MenuBundle\Entity\MenuTaxonomy $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu
     *
     * @return CAF\MenuBundle\Entity\MenuTaxonomy 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    public function displayMenuPicto($menus_picto, $base_url) {
        if (empty($menus_picto))
            return '';

        $html = '<ul class="child lvl_2 menu_picto">';
        foreach($menus_picto as $entry) {
            $url = '';
                                 
            if(isset($entry['urls_content'])){
                $url = $entry['urls_content'];
            }

            if ($url == '') {
                $url = $entry['urls'];
            }

            $link = '';
            $start_url = substr($url,0,7);
            if($start_url == 'http://') {
                $link = $url;
            } else {
                $link = $base_url.$url;
            } 
            $media = $entry['media'];
            $html .= '<li class="lvl_2 picto"><a href="'.$link.'"><table><tr><td><img src="/web/'.$media['image'].'" alt="'.$media['alt'].'" title="'.$media['title'].'" style="float:left"/></td><td>'.$entry['title'].'</td></tr></table></a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}