<?php

namespace CAF\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\AdminBundle\Entity\Select
 *
 * @ORM\Table(name="select")
 * @ORM\Entity()
 */
class Select
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
     * @var string $category
     * 
     * @ORM\Column(name="category", type="string")
     */
    private $category;

    /**
     * @var string $content
     * 
     * @ORM\Column(name="content", type="string")
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


    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Select
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    
        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set libelle2
     *
     * @param string $libelle2
     * @return Select
     */
    public function setLibelle2($libelle2)
    {
        $this->libelle2 = $libelle2;
    
        return $this;
    }

    /**
     * Get libelle2
     *
     * @return string 
     */
    public function getLibelle2()
    {
        return $this->libelle2;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Select
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Select
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
}