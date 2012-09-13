<?php

namespace CAF\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\FormBundle\Entity\FormTaxonomy
 *
 * @ORM\Table(name="formtaxonomy")
 * @ORM\Entity(repositoryClass="CAF\FormBundle\Entity\Repository\FormTaxonomyRepository")
 */
class FormTaxonomy
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;


    /**
     * @ORM\OneToMany(targetEntity="Formulaire", mappedBy="id_form_taxonomy")
     */
    protected $formulaires;

    /**
     * @ORM\ManyToMany(targetEntity="FormFields", mappedBy="form_taxonomies", cascade={"persist"})
     * @ORM\JoinTable(name="form_fields_taxonomy")
     */
    protected $formfields;

    /**
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    private $template;


    public function __toString()
    {
        return $this->libelle;
    }


    public function __construct()
    {
        $this->formulaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return FormTaxonomy
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
     * Set published
     *
     * @param boolean $published
     * @return FormTaxonomy
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
     * Set template
     *
     * @param string $template
     * @return FormTaxonomy
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    
        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Remove formulaires
     *
     * @param CAF\FormBundle\Entity\Formulaire $formulaires
     */
    public function removeFormulaire(\CAF\FormBundle\Entity\Formulaire $formulaires)
    {
        $this->formulaires->removeElement($formulaires);
    }

    /**
     * Get formulaires
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFormulaires()
    {
        return $this->formulaires;
    }

    /**
     * Add formfields
     *
     * @param CAF\FormBundle\Entity\FormFields $formfields
     * @return FormTaxonomy
     */
    public function addFormfield(\CAF\FormBundle\Entity\FormFields $formfields)
    {
        $this->formfields[] = $formfields;
    
        return $this;
    }

     /**
     * Set fields
     */
    public function setFormFields(\Doctrine\Common\Collections\Collection $formfields)
    {

        foreach ($this->formfields as $formfield) { // On parcours les anciens fields liés
            $formfield->removeFormTaxonomie($this);   
        }
        
 
        $this->formfields = $formfields;
        foreach ($this->formfields as $formfield){
            $formfield->addFormTaxonomie($this);
        }
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

    /**
     * Add formulaires
     *
     * @param CAF\FormBundle\Entity\Formulaire $formulaires
     * @return FormTaxonomy
     */
    public function addFormulaire(\CAF\FormBundle\Entity\Formulaire $formulaires)
    {
        $this->formulaires[] = $formulaires;
    
        return $this;
    }
}