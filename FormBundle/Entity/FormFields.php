<?php

namespace CAF\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\FormBundle\Entity\FormFields
 *
 * @ORM\Table(name="formfields")
 * @ORM\Entity(repositoryClass="CAF\FormBundle\Entity\Repository\FormFieldsRepository")
 */
class FormFields
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var integer $id_form_field_taxonomy
     *
     * @ORM\ManyToOne(targetEntity="FormFieldTaxonomy")
     * @ORM\JoinColumn(name="id_form_field_taxonomy", referencedColumnName="id")
     */
    private $id_form_field_taxonomy;

    /**
     * @var int ordre
     * 
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;

    /**
     * @var boolean required
     * 
     * @ORM\Column(name="required", type="boolean")
     */
    private $required;

     /**
     * @var boolean published
     * 
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @ORM\ManyToMany(targetEntity="FormTaxonomy", inversedBy="fields")
     * @ORM\JoinTable(name="form_fields_taxonomy")
     */
    private $form_taxonomies;

    /**
     * @ORM\OneToMany(targetEntity="FormFieldsValue", mappedBy="formfield", cascade={"remove", "persist"})
     * @ORM\OrderBy({"ordre" = "ASC"})
     */
    private $formfieldsvalue;


    public function __toString()
    {
        return $this->name;
    }



    public function __construct()
    {
        $this->form_taxonomies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formfieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
   
    /**
     * Remove form_taxonomies
     *
     * @param CAF\FormBundle\Entity\FormTaxonomy $formTaxonomies
     */
    public function removeFormTaxonomie(\CAF\FormBundle\Entity\FormTaxonomy $formTaxonomies)
    {
        $this->form_taxonomies->removeElement($formTaxonomies);
    }

    /**
     * Add formfieldsvalue
     *
     * @param CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue
     * @return FormFields
     */
    public function addFormFieldsvalue(\CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue)
    {
        $this->formfieldsvalue[] = $formfieldsvalue;
    
        return $this;
    }

    /**
     * Remove formfieldsvalue
     *
     * @param CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue
     */
    public function removeFormFieldsvalue(\CAF\FormBundle\Entity\FormFieldsValue $formfieldsvalue)
    {
        $this->formfieldsvalue->removeElement($formfieldsvalue);
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
     * @return FormFields
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
     * Set libelle
     *
     * @param string $libelle
     * @return FormFields
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
     * Set ordre
     *
     * @param integer $ordre
     * @return FormFields
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    
        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return FormFields
     */
    public function setRequired($required)
    {
        $this->required = $required;
    
        return $this;
    }

    /**
     * Get required
     *
     * @return boolean 
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return FormFields
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
     * Set id_form_field_taxonomy
     *
     * @param CAF\FormBundle\Entity\FormFieldTaxonomy $idFormFieldTaxonomy
     * @return FormFields
     */
    public function setIdFormFieldTaxonomy(\CAF\FormBundle\Entity\FormFieldTaxonomy $idFormFieldTaxonomy = null)
    {
        $this->id_form_field_taxonomy = $idFormFieldTaxonomy;
    
        return $this;
    }

    /**
     * Get id_form_field_taxonomy
     *
     * @return CAF\FormBundle\Entity\FormFieldTaxonomy 
     */
    public function getIdFormFieldTaxonomy()
    {
        return $this->id_form_field_taxonomy;
    }

    /**
     * Add form_taxonomies
     *
     * @param CAF\FormBundle\Entity\FormTaxonomy $formTaxonomies
     * @return FormFields
     */
    public function addFormTaxonomie(\CAF\FormBundle\Entity\FormTaxonomy $formTaxonomies)
    {
        $this->form_taxonomies[] = $formTaxonomies;
    
        return $this;
    }

    /**
     * Get form_taxonomies
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFormTaxonomies()
    {
        return $this->form_taxonomies;
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

}