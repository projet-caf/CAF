<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\BlocBundle\Entity\BlocHtml
 *
 * @ORM\Table(name="blochtml")
 * @ORM\Entity
 */
class BlocHtml
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
     * @var text $content
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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

    public function html($position, $bloc, $lang, $key, $size){
        $key++;
        $html=($position == 'left') ?'<div class="left_block">':'<div class="bloc_html_content">';
        $html.='<h3 class="title_bls wth_ar">'.$this->bloc->getTitle().'</h3>';
        $html.=($position == 'left') ?'<div class="sep_degrad">':'<div class="sep_degrad_md">';
        $html.='</div><div class="block_content_text">'.$this->getContent().'</div>';
        $html.='</div>';
        if($key == $size){
            $html.=($position == 'left') ?'<div class="left_block_bottom"></div>':'';
        }else{
            $html.=($position == 'left') ?'<div class="left_block_bottom grey_bck"></div>':'';
        }
        return $html;
    }

    public function getType(){
        return 'BlocHtml';
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
}