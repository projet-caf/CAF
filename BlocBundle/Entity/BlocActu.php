<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CAF\BlocBundle\Entity\BlocActu
 *
 * @ORM\Table(name="blocactu")
 * @ORM\Entity
 */
class BlocActu
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
     * @var integer $limit_value
     *
     * @ORM\Column(name="limit_value", type="integer")
     */
    private $limit_value;

    /**
    * @ORM\OneToOne(targetEntity="CAF\ContentBundle\Entity\Category")
    */
    private $category;

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

    public function html($path_web, $actus, $unique, $lang, $base_url){
        switch ($this->display_type) {
            case 'actu':
                $str = '';
                $title = '';
                $desc = '';
                $date_news = '';
                $str .= '<div class="bloc_news_content">';
                $str .= '<h3 class="title_bls">'.$this->bloc->getTitle().'</h3>';
                $str .= '<div class="sep_degrad_md"></div>';
                foreach($actus as $actu) {
                    $translations = $actu->getTranslations();
                    foreach($translations as $t) {
                        if($t->getLang()->getCode() == $lang) {

                            $title = $t->getTitle();
                            foreach($t->getFieldsValue() as $fieldvalue) {
                                $field_name = $fieldvalue->getField()->getName();
                                switch($field_name) {
                                    case 'date_news':
                                        $date_news = $fieldvalue->getValue();
                                        break;
                                    case 'image':
                                        $image = $fieldvalue->getValue();
                                        break;
                                    case 'description_courte':
                                        $desc = $fieldvalue->getValue();
                                        break;   
                                }
                            }
                            $content_urls = $t->getContentUrls();
                            $url = $content_urls[0]->getUrl();
                            $str .= '<div class="bloc_news">';
                            $str .= '<div class="bloc_news_image">';
                            $str .= '<a href="'.$base_url.$url.'">';
                            if($image['image']){
                                $str .= '<img src="'.$path_web.'/'.$image['image'].'" alt="'.$image['alt'].'" title="'.$image['title'].'" width="64px" height="64px"/>';
                            }
                            $str .= '</a></div><div class="bloc_news_text"><span class="italic">'.$date_news.'</span><span class="title_news"><a href="'.$base_url.$url.'">'.$title.'</a></span><span class="introtext_news">'.substr(strip_tags($desc), 0, 100).'</span></div><div class="clear10"></div></div>';
                        }    
                    }            
                    
                }           
                $str .= '</div>';
            break;
            case 'document':
                $str = '';
                $title = '';
                $desc = '';
                $date_news = '';
                $str .= '<div class="bloc_document_content">';
                $str .= '<h3 class="title_bls">'.$this->bloc->getTitle().'</h3>';
                $str .= '<div class="sep_degrad_md"></div>';

                foreach($actus as $actu) {
                    $translations = $actu->getTranslations();
                    foreach($translations as $t) {

                        if($t->getLang()->getCode() == $lang) {
                            $title = $t->getTitle();
                            foreach($t->getFieldsValue() as $fieldvalue) {
                                $field_name = $fieldvalue->getField()->getName();
                                switch($field_name) {
                                    case 'description_courte':
                                        $desc = $fieldvalue->getValue();
                                        break;   
                                    case 'fichier':
                                        $file = $fieldvalue->getValue();
                                        break;  
                                }
                            }
                            $content_urls = $t->getContentUrls();
                            $url = $content_urls[0]->getUrl();
                            $str .= '<div class="bloc_news">';
                            $str .= '<div class="bloc_news_image">';
                            $str .= '<a href="'.$base_url.$url.'">';
                            $str .= '<img src="'.$path_web.'/bundles/caffront/images/picto-pdf.png" />';
                            $str .= '</a></div><div class="bloc_news_text"><span class="title_news"><a href="'.$base_url.$url.'">'.$title.'</a></span><span class="introtext_news">'.substr(strip_tags($desc), 0, 100).'</span></div><div class="clear10"></div></div>';
                        }    

                    }             
                    
                }
                $str .= '</div>';
                break;
        }
        return $str;
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

    public function getType(){
        return 'BlocActu';
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
     * Set category
     *
     * @param CAF\ContentBundle\Entity\Category $category
     */
    public function setCategory(\CAF\ContentBundle\Entity\Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return CAF\ContentBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set limit_value
     *
     * @param integer $limitValue
     */
    public function setLimitValue($limitValue)
    {
        $this->limit_value = $limitValue;
    }

    /**
     * Get limit_value
     *
     * @return integer 
     */
    public function getLimitValue()
    {
        return $this->limit_value;
    }

    /**
     * Set display_type
     *
     * @param string $displayType
     * @return BlocActu
     */
    public function setDisplayType($displayType)
    {
        $this->display_type = $displayType;
    
        return $this;
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
}