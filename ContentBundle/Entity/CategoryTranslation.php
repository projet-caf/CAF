<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CAF\ContentBundle\Entity\ContentTranslation
 *
 * @ORM\Table(name="category_translation")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\CategoryTranslationRepository")
 */
class CategoryTranslation
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
     * @var boolean $published
     * 
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var string $title
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     **/
    private $title;

    /**
     * @var string $alias
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var text $description
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
   /**
     * @ORM\ManyToMany(targetEntity="CAF\AdminBundle\Entity\Country", inversedBy="content", cascade={"persist"})
     * @ORM\JoinTable(name="category_country")
     */
    private $countries;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\AdminBundle\Entity\Language")
     * @ORM\JoinColumn(name="lang")
     */
    private $lang;

     /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="translations")
     * @ORM\JoinColumn(name="item_id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="MetasValueCategory", mappedBy="category_translation", cascade={"remove", "persist"})
     */
    private $metasvalue;

    /**
     * @ORM\ManyToMany(targetEntity="ContentTranslation", mappedBy="categories")
     */
    private $contents;

    /**
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;


    
    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->metasvalue = new \Doctrine\Common\Collections\ArrayCollection();
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
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * Add countries
     *
     * @param CAF\ContentBundle\Entity\Country $countries
     */
    public function addCountry(\CAF\AdminBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;
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
     */
    public function setLang(\CAF\AdminBundle\Entity\Language $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Get lang
     *
     * @return CAF\ContentBundle\Entity\Language 
     */
    public function getLang()
    {
        return $this->lang;
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
     * Get metasvalue
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetasvalue()
    {
        return $this->metasvalue;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add metasvalue
     *
     * @param CAF\ContentBundle\Entity\MetasValueCategory $metasvalue
     */
    public function addMetasValueCategory(\CAF\ContentBundle\Entity\MetasValueCategory $metasvalue)
    {
        $this->metasvalue[] = $metasvalue;
    }

    /**
     * Set published
     *
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
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
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    

    /**
     * Add countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     * @return CategoryTranslation
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
     * Add metasvalue
     *
     * @param CAF\ContentBundle\Entity\MetasValueCategory $metasvalue
     * @return CategoryTranslation
     */
    public function addMetasvalue(\CAF\ContentBundle\Entity\MetasValueCategory $metasvalue)
    {
        $this->metasvalue[] = $metasvalue;
    
        return $this;
    }

    /**
     * Remove metasvalue
     *
     * @param CAF\ContentBundle\Entity\MetasValueCategory $metasvalue
     */
    public function removeMetasvalue(\CAF\ContentBundle\Entity\MetasValueCategory $metasvalue)
    {
        $this->metasvalue->removeElement($metasvalue);
    }

    /**
     * Add contents
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contents
     * @return CategoryTranslation
     */
    public function addContent(\CAF\ContentBundle\Entity\ContentTranslation $contents)
    {
        $this->contents[] = $contents;
    
        return $this;
    }

    /**
     * Remove contents
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contents
     */
    public function removeContent(\CAF\ContentBundle\Entity\ContentTranslation $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContents()
    {
        return $this->contents;
    }

    public function __toString()
    {
        return $this->title;
    }


    /**
     * Set url
     *
     * @param string $url
     * @return CategoryTranslation
     */
    public function setUrl($url='')
    {
        $metasvalues = $this->getMetasvalue();
        foreach($metasvalues as $metavalue) {
            $meta = $metavalue->getMeta();
            if($meta->getType() == 'other' && $meta->getName() == 'Url') {
                $url = $metavalue->getValue();
            }
        }
        if($url != '')  
            $this->url = $url;
        else
            $this->url = $this->alias.'.html';    
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
}