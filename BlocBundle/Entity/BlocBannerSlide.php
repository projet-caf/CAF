<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CAF\BlocBundle\Entity\BlocBannerSlide
 *
 * @ORM\Table(name="blocbannerslide")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class BlocBannerSlide
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
     * @var array images
     * @ORM\Column(name="images", type="array", nullable=true)
     */
    private $images;

    /**
     * @var array path
     * @ORM\Column(name="path", type="array", nullable=true, length=1000)
    */
    private $path;

    private $temp_path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var string url
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

    /**
     * @var integer size
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;


        /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct(){
        $bloc = new Bloc();
        $this->setBloc($bloc);
    }

    public function html($bloc, $path_web, $display_menu){
        $class='';
        if($display_menu){
            $class = '20';
        }else{
            $class = '10';
        }
        $images = $this->images; 

        $html = '<div class="ban_ht20 ban'.$class.'"><div class="slides">';
        
        $i = 1;
        foreach ($images as $key => $image) {
            $img = $image['image']['image'];
            $alt = $image['image']['alt'];
            $title = $image['image']['title'];
            $fline = $image['first_line'];
            $sline = $image['second_line'];
            $html .= '<div class="slide item-'.$i.'">';
            $html .= '<div class="ban_image"><img src="'.$path_web.'/'.$img.'" alt="'.$alt.'" title="'.$title.'" /></div>';
            $html .= '<div class="ban_text">';
            $html .= '<div class="line_orange line">'.$fline.'</div>';
            $html .= '<div class="line_black line">'.$sline.'</div>';
            $html .= '<div class="knmr line"><span>En savoir plus</span></div>';
            $html .= '</div></div>';
            $i++;
        }

        $html .= '<div class="ban_btn_navi"></div></div>';

        $html .=   '<script type="text/javascript">
                    var slide_in = 1;
                    var nb_slides = $(".slides .slide").length;
                    $(document).ready(function(){
                        var nb_slides = $(".slides .slide").length;
                        var timer = setInterval("next()", 2500);    
                        var html = \'\';
                        if(nb_slides!=1){
                            $(".slides .slide").hide();
                            $(".slides .item-"+slide_in).show();
                            for(i=1;i<=nb_slides;i++){
                                html += \'<div class="btn_slide btn_\'+i+\'"><a href="#" onclick="change_slide(\'+i+\');"></a></div>\';
                            }
                            $(".ban_btn_navi").html(html);
                            $(".ban_btn_navi .btn_"+slide_in).addClass( \'active\' );
                        }   

                        $("#slides").mouseover(function() {
                            clearInterval(timer);
                        });
                        
                        $("#slides").mouseout(function() {
                            timer = setInterval("next()", 2500);
                        });
                    })

                    function change_slide(slide){
                        slide_in = slide;
                        $(".slides .slide").hide();
                        $(".slides .item-"+slide_in).show();
                        $(".ban_btn_navi .btn_slide").removeClass( \'active\' );
                        $(".ban_btn_navi .btn_"+slide_in).addClass( \'active\' );
                    }

                    function next(){
                        slide_in++;
                        $(".slides .slide").hide();     
                        $(".ban_btn_navi .btn_slide").removeClass( \'active\' );
                        if(slide_in>nb_slides){
                            slide_in=1;
                        }
                        $(".slides .item-"+slide_in).show();
                        $(".ban_btn_navi .btn_"+slide_in).addClass( \'active\' );
                    }
                    </script>';

        $this->html = $html;
        return $this;
    }

    public function getType(){
        return 'BlocBannerSlide';
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
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
     * Set image
     *
     * @param array $image
     * @return BlocBannerSlide
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return array 
     */
    public function getImage()
    {
        return $this->image;
    }


     /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {   
        //var_dump($this->images);die();
        if(!empty($this->path)){
            $array=unserialize($this->path);
            //var_dump($array);die();
        }else{
            $array=array();
        }
            //var_dump($this->images);
            foreach ($this->images as $key => $image) {
                $image = $image['image']['image'];
                if (null != $image) {

                    $this->temp_path = uniqid().'.'.$image->guessExtension();
                    $this->file = $image;
                    $array[$key] = $this->getUploadDir().$this->temp_path;
                    $path = $this->getUploadDir().$this->temp_path;
                    $this->images[$key]['image']['image'] = $path;

                    if (!empty($this->file)) {
                        $this->file->move($this->getUploadRootDir(), $this->temp_path);
                        unset($this->file);
                    }
                }
                else {
                    if(empty($this->path[$key])){
                        $array[$key] = '';
                    }else{
                        $this->images[$key]['image']['image'] = $array[$key];
                    }
                }
            }

            
        $this->path = serialize($array);

        $this->size = count($this->images);

    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->temp_path ? null : $this->getUploadRootDir().'/'.$this->temp_path;
    }

    public function getWebPath()
    {
        return null === $this->temp_path ? null : $this->getUploadDir().'/'.$this->temp_path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'medias/banners/uploads/banners_bloc/banners/';
    }

    /**
     * Set path
     *
     * @param string $path
     * @return BlocBannerSlide
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set legend
     *
     * @param array $legend
     * @return BlocBannerSlide
     */
    public function setLegend($legend)
    {
        $this->legend = $legend;
    
        return $this;
    }

    /**
     * Get legend
     *
     * @return array 
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return BlocBannerSlide
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set images
     *
     * @param array $images
     * @return BlocBannerSlide
     */
    public function setImages($images)
    {
        $this->images = $images;
    
        return $this;
    }

    /**
     * Get images
     *
     * @return array 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return BlocBannerSlide
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }
}