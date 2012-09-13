<?php

namespace CAF\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\FormBundle\Entity\Formulaire
 *
 * @ORM\Table(name="formulaire")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="CAF\FormBundle\Entity\Repository\FormulaireRepository")
 */
class Formulaire
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
     * @var integer $id_form_taxonomy
     *
     * @ORM\ManyToOne(targetEntity="FormTaxonomy", inversedBy="formulaires")
     * @ORM\JoinColumn(name="id_form_taxonomy", referencedColumnName="id")
     */
    private $id_form_taxonomy;

    /**
     * @ORM\OneToMany(targetEntity="FormFieldsValue", mappedBy="formulaire", cascade={"remove", "persist"})
     */
    private $formfieldsvalue;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

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

    public $values;


     public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formfieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set published
     *
     * @param boolean $published
     * @return Formulaire
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
     * Set id_form_taxonomy
     *
     * @param CAF\FormBundle\Entity\FormTaxonomy $idFormTaxonomy
     * @return Formulaire
     */
    public function setIdFormTaxonomy(\CAF\FormBundle\Entity\FormTaxonomy $idFormTaxonomy = null)
    {
        $this->id_form_taxonomy = $idFormTaxonomy;
    
        return $this;
    }

    /**
     * Get id_form_taxonomy
     *
     * @return CAF\FormBundle\Entity\FormTaxonomy 
     */
    public function getIdFormTaxonomy()
    {
        return $this->id_form_taxonomy;
    }


    /**
     * Get values
     *
     * @return array 
     */
    public function getValues()
    {
        return $this->values;
    }


    /**
     * Set values
     *
     * @return array 
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }


    /**
     * Add formfieldsvalue
     *
     * @param CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue
     * @return Formulaire
     */
    public function addFormfieldsvalue(\CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue)
    {
        $this->formfieldsvalue[] = $formfieldsvalue;
    
        return $this;
    }

    /**
     * Remove formfieldsvalue
     *
     * @param CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue
     */
    public function removeFormfieldsvalue(\CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue)
    {
        $this->formfieldsvalue->removeElement($formfieldsvalue);
    }

    /**
     * Get formfieldsvalue
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFormfieldsvalue()
    {
        return $this->formfieldsvalue;
    }

     /**
     * Reset
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function resetFormfieldsvalue()
    {
        $this->formfieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();  
        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Formulaire
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
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Formulaire
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
     * Set updatedValue
     *
     * @ORM\PreUpdate()
     */
    public function setUpdatedValue()
    {
        $this->updated = new \DateTime();
    }

    /**
     * Set createdValue
     * 
     * @ORM\PrePersist()
     */
    public function setCreatedValue()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }



}