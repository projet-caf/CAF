<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\FieldTaxonomy
 *
 * @ORM\Table(name="fieldtaxonomy")
 * @ORM\Entity
 */
class FieldTaxonomy
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $balise
     *
     * @ORM\Column(name="balise", type="text")
     */
    private $balise;


    /**
     * @ORM\OneToMany(targetEntity="Fields", mappedBy="id_field_taxonomy")
     */
    protected $fields;


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
     * Set balise
     *
     * @param string $balise
     */
    public function setBalise($balise)
    {
        $this->balise = $balise;
    }

    /**
     * Get balise
     *
     * @return string 
     */
    public function getBalise()
    {
        return $this->balise;
    }


    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add fields
     *
     * @param CAF\ContentBundle\Entity\Fields $fields
     */
    public function addFields(\CAF\ContentBundle\Entity\Fields $fields)
    {
        $this->fields[] = $fields;
    }

    /**
     * Get fields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFields()
    {
        return $this->fields;
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
     * Add fields
     *
     * @param CAF\ContentBundle\Entity\Fields $fields
     * @return FieldTaxonomy
     */
    public function addField(\CAF\ContentBundle\Entity\Fields $fields)
    {
        $this->fields[] = $fields;
    
        return $this;
    }

    /**
     * Remove fields
     *
     * @param CAF\ContentBundle\Entity\Fields $fields
     */
    public function removeField(\CAF\ContentBundle\Entity\Fields $fields)
    {
        $this->fields->removeElement($fields);
    }
}