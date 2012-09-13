<?php
namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CAF\AdminBundle\Entity\Country;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CAF\ContentBundle\Entity\Translation
 *
 * @ORM\Table(name="contenttranslation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\ContentTranslationRepository")
 */
class ContentTranslation
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
     * @var string $title
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     **/
    private $title;

    /**
     * @var string $alias
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var boolean $published
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

   /**
     * @ORM\ManyToMany(targetEntity="CAF\AdminBundle\Entity\Country", inversedBy="content", cascade={"persist"})
     * @ORM\JoinTable(name="content_country")
     */
    private $countries;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\AdminBundle\Entity\Language")
     * @ORM\JoinColumn(name="lang")
     */
    private $lang;

    /**
     * @var array $urls
     *
     * @ORM\Column(name="urls", type="array")
     */
    private $urls;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

     /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="translations")
     * @ORM\JoinColumn(name="item_id")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="MetasValue", mappedBy="content_translation", cascade={"remove", "persist"})
     */
    private $metasvalue;

    /**
     * @ORM\OneToMany(targetEntity="FieldsValue", mappedBy="content_translation", cascade={"remove", "persist"})
     */
    private $fieldsvalue;

    /**
     * @ORM\ManyToMany(targetEntity="CategoryTranslation", inversedBy="contents", cascade={"persist"})
     * @ORM\JoinTable(name="content_category")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="UrlsContent", mappedBy="content_translation", cascade={"remove", "persist"})
     */
    private $content_urls;


    

    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->metasvalue = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Set urls
     *
     * @param array $urls
     */
    public function setUrls($urls = array())
    {
        if(!empty($urls))
            $this->urls = $urls;
        else {
            $this->urls = array();
            $countries = $this->getCountries();
            $categories = $this->getCategories();
            foreach($countries as $country) {
                foreach($categories as $category) {
                    $this->urls[] = $country->getCode().'/'.$category->getAlias().'/'.$this->stringURLSafe($this->title);
                }
            }
        }
    }

    private function transliterate($string)
    {
        $string = htmlentities(utf8_decode($string));
        $string = preg_replace(
            array('/&szlig;/','/&(..)lig;/', '/&([aouAOU])uml;/','/&(.)[^;]*;/'),
            array('ss',"$1","$1".'e',"$1"),
            $string);

        return $string;
    }

    private function stringURLSafe($string)
    {
        //remove any '-' from the string they will be used as concatonater
        $str = str_replace('-', ' ', $string);

        $str = $this->transliterate($str);

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);

        // lowercase and trim
        $str = trim(strtolower($str));
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

    /**
     * Set title
     *
     * @param string $title
     * @return ContentTranslation
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set alias
     *
     * @param string $alias
     */
    public function setAlias($alias='')
    {
        if($alias != '')
            $this->alias = $alias;
        else
            $this->alias = $this->stringURLSafe($this->title);

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return ContentTranslation
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get urls
     *
     * @return array 
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ContentTranslation
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     * @param \DateTime $updated
     * @return ContentTranslation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set createdValue
     * 
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * Set updatedValue
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->updated = new \DateTime();
    }

    /**
     * Add countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     * @return ContentTranslation
     */
    public function addCountrie(\CAF\AdminBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     */
    public function removeCountrie(\CAF\AdminBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Set lang
     *
     * @param CAF\AdminBundle\Entity\Language $lang
     * @return ContentTranslation
     */
    public function setLang(\CAF\AdminBundle\Entity\Language $lang = null)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return CAF\AdminBundle\Entity\Language 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set content
     *
     * @param CAF\ContentBundle\Entity\Content $content
     * @return ContentTranslation
     */
    public function setContent(\CAF\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return CAF\ContentBundle\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add metasvalue
     *
     * @param CAF\ContentBundle\Entity\MetasValue $metasvalue
     * @return ContentTranslation
     */
    public function addMetasvalue(\CAF\ContentBundle\Entity\MetasValue $metasvalue)
    {
        $this->metasvalue[] = $metasvalue;
    
        return $this;
    }

    /**
     * Remove metasvalue
     *
     * @param CAF\ContentBundle\Entity\MetasValue $metasvalue
     */
    public function removeMetasvalue(\CAF\ContentBundle\Entity\MetasValue $metasvalue)
    {
        $this->metasvalue->removeElement($metasvalue);
    }

    /**
     * Get metasvalue
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetasvalue()
    {
        return $this->metasvalue;
    }

    /**
     * Add fieldsvalue
     *
     * @param CAF\ContentBundle\Entity\FieldsValue $fieldsvalue
     * @return ContentTranslation
     */
    public function addFieldsvalue(\CAF\ContentBundle\Entity\FieldsValue $fieldsvalue)
    {
        $this->fieldsvalue[] = $fieldsvalue;
    
        return $this;
    }

    /**
     * Remove fieldsvalue
     *
     * @param CAF\ContentBundle\Entity\FieldsValue $fieldsvalue
     */
    public function removeFieldsvalue(\CAF\ContentBundle\Entity\FieldsValue $fieldsvalue)
    {
        $this->fieldsvalue->removeElement($fieldsvalue);
    }

    /**
     * Get fieldsvalue
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFieldsvalue()
    {
        return $this->fieldsvalue;
    }

    public function setFieldsValue(\Doctrine\Common\Collections\Collection $fieldsvalue)
    {
        $this->fieldsvalue = $fieldsvalue;
    }

    /**
     * Add categories
     *
     * @param CAF\ContentBundle\Entity\Category $categories
     * @return ContentTranslation
     */
    public function addCategorie(\CAF\ContentBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param CAF\ContentBundle\Entity\Category $categories
     */
    public function removeCategorie(\CAF\ContentBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories(\Doctrine\Common\Collections\Collection $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Add content_urls
     *
     * @param CAF\ContentBundle\Entity\UrlsContent $contentUrls
     * @return ContentTranslation
     */
    public function addContentUrl(\CAF\ContentBundle\Entity\UrlsContent $contentUrls)
    {
        $this->content_urls[] = $contentUrls;
    
        return $this;
    }

    /**
     * Remove content_urls
     *
     * @param CAF\ContentBundle\Entity\UrlsContent $contentUrls
     */
    public function removeContentUrl(\CAF\ContentBundle\Entity\UrlsContent $contentUrls)
    {
        $this->content_urls->removeElement($contentUrls);
    }

    /**
     * Get content_urls
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContentUrls()
    {
        return $this->content_urls;
    }

    public function setContentUrls($content_urls = null)
    {
        if($content_urls == null) {
            $this->content_urls = new \Doctrine\Common\Collections\ArrayCollection();
            foreach($this->categories as $category) {
                $this->addContentUrl($this->setContentUrl($category));
            }
        } else {
            foreach($this->content_urls as $content_url) {
                foreach($this->categories as $category) {
                    $content_url = $this->setContentUrl($category,$content_url);
                }
            }
        }
    }

    /**
     * Set url
     *
     * @return UrlsContent
     */
    private function setContentUrl($category,$content_url=null)
    {
        $metasvalues = $this->getMetasvalue();
        $url = '';
        if ($content_url==null) {
            $content_url = new UrlsContent();
            $content_url->setContentTranslation($this);
        }    
        foreach($metasvalues as $metavalue) {
            $meta = $metavalue->getMeta();
            if($meta->getType() == 'other' && $meta->getName() == 'Url') {
                $url = $metavalue->getValue();
            }
        }

        if($url == '') {
            $metasvalues = $category->getMetasvalue();
            foreach($metasvalues as $metavalue) {
                $meta = $metavalue->getMeta();
                if($meta->getType() == 'other' && $meta->getName() == 'Url') {
                    $url = $metavalue->getValue();
                }
            }
            if($url == '')
                $url = $category->getAlias().'/';
            $url .= $this->getAlias().'.html';
        }
        $content_url->setUrl($url);
    
        return $content_url;
    }

}