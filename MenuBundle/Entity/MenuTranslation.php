<?php

namespace CAF\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="menu_translations_ext",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class MenuTranslation extends AbstractPersonalTranslation
{
    /**
     * Convinient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $locale
     * @ORM\Column(name="locale")
     */
    protected $locale;

    /**
     * @var string $field
    * @ORM\Column(name="field")
     */
    protected $field;

    /**
     * @var string $category
     * @ORM\Column(name="category")
     */
    protected $category;    
    
    /**
     * @var string $content
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @ORM\ManyToMany(targetEntity="CAF\AdminBundle\Entity\Country", inversedBy="menus")
     **/
    private $countries;
	
	
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
     * Set locale
     *
     * @param string $locale
     * @return MenuTranslation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return MenuTranslation
     */
    public function setField($field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return MenuTranslation
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set object
     *
     * @param CAF\MenuBundle\Entity\Menu $object
     * @return MenuTranslation
     */
    public function setObject($object)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return CAF\MenuBundle\Entity\Menu 
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set category
     *
     * @param CAF\MenuBundle\Entity\Menu $category
     * @return MenuTranslation
     */
    public function setCategory(\CAF\MenuBundle\Entity\Menu $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return CAF\MenuBundle\Entity\Menu 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     * @return MenuTranslation
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
}