<?php

namespace CAF\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\AdminBundle\Entity\Config\Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="CAF\MenuBundle\Entity\MenuTranslation", mappedBy="countries")
     **/
    private $menus;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
    public function __construct()
    {
        $this->menus = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add menus
     *
     * @param CAF\AdminBundle\Entity\MenuTranslation $menus
     */
    public function addMenuTranslation(\CAF\MenuBundle\Entity\MenuTranslation $menus)
    {
        $this->menus[] = $menus;
    }

    /**
     * Get menus
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Add menus
     *
     * @param CAF\MenuBundle\Entity\MenuTranslation $menus
     * @return Country
     */
    public function addMenu(\CAF\MenuBundle\Entity\MenuTranslation $menus)
    {
        $this->menus[] = $menus;
    
        return $this;
    }

    /**
     * Remove menus
     *
     * @param CAF\MenuBundle\Entity\MenuTranslation $menus
     */
    public function removeMenu(\CAF\MenuBundle\Entity\MenuTranslation $menus)
    {
        $this->menus->removeElement($menus);
    }
}