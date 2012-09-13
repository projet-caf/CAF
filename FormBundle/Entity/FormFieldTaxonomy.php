<?php

namespace CAF\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\FormBundle\Entity\FormFieldTaxonomy
 *
 * @ORM\Table(name="formfieldtaxonomy")
 * @ORM\Entity
 */
class FormFieldTaxonomy
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
     * @ORM\OneToMany(targetEntity="FormFields", mappedBy="id_form_field_taxonomy")
     */
    protected $formfields;
    
    public function __toString()
    {
        return $this->name;
    }


    public function __construct()
    {
        $this->formfields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return FormFieldTaxonomy
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * Set balise
     *
     * @param string $balise
     * @return FormFieldTaxonomy
     */
    public function setBalise($balise)
    {
        $this->balise = $balise;
    
        return $this;
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

    /**
     * Add formfields
     *
     * @param CAF\FormBundle\Entity\FormFields $formfields
     * @return FormFieldTaxonomy
     */
    public function addFormfield(\CAF\FormBundle\Entity\FormFields $formfields)
    {
        $this->formfields[] = $formfields;
    
        return $this;
    }

    /**
     * Remove formfields
     *
     * @param CAF\FormBundle\Entity\FormFields $formfields
     */
    public function removeFormfield(\CAF\FormBundle\Entity\FormFields $formfields)
    {
        $this->formfields->removeElement($formfields);
    }

    /**
     * Get formfields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFormfields()
    {
        return $this->formfields;
    }
}