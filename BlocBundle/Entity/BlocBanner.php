<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CAF\BlocBundle\Entity\BlocBanner
 *
 * @ORM\Table(name="blocbanner")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class BlocBanner
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
     * @var array image
     * @ORM\Column(name="image", type="array", nullable=true)
     */
    private $image;

    /**
     * @var string path
     * @ORM\Column(name="path", type="string", nullable=true)
    */
    private $path;

    private $temp_path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

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
        $this->path="media/banners";
    }

    public function html($path_web, $display_menu, $html_menu){
        $class='';
        if($display_menu){
            $class = '20';
        }else{
            $class = '10';
        }
        $html = '<div class="ban_ht10 ban'.$class.'"><div class="slides">';
        $html .= '<div class="ban_image"><img src="'.$path_web.'/'.$this->path.'" alt="'.$this->image['alt'].'" title="'.$this->image['title'].'"/></div>';
        $html .= '</div>';
        $html .= $html_menu;
        $html .= '</div>';
        $this->html = $html;
        return $this;
    }

    public function getType(){
        return 'BlocBanner';
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
     * @return BlocBanner
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
        if (null != $this->image['image']) {

            $this->temp_path = uniqid().'.'.$this->image['image']->guessExtension();
            $this->file = $this->image['image'];
            $this->path = $this->getUploadDir().$this->temp_path;
            $this->image['image'] = $this->path;
        }
        else {
            if(empty($this->path)){
                $this->path = '';
            }else{
                $this->image['image'] = $this->path;
            }
        }

        //var_dump($this->path);die();

        //var_dump($this->image);die();
        //var_dump(get_object_vars($this->image['image']));die();
        //$this->image = ($this->image);

    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->temp_path);

        unset($this->file);
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
        return 'uploads/banners_bloc/banners/';
    }

    /**
     * Set path
     *
     * @param string $path
     * @return BlocBanner
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
}