<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\ContentTaxonomy
 *
 * @ORM\Table(name="contenttaxonomy")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\ContentTaxonomyRepository")
 */
class ContentTaxonomy
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
     * @ORM\OneToMany(targetEntity="Content", mappedBy="id_content_taxonomy")
     */
    protected $contents;

    /**
     * @ORM\ManyToMany(targetEntity="Fields", mappedBy="content_taxonomies", cascade={"persist"})
     * @ORM\JoinTable(name="fields_content_taxonomy")
     */
    protected $fields;

    /**
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    private $template;


    public function __construct()
    {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
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
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     * Add contents
     *
     * @param CAF\ContentBundle\Entity\Content $contents
     */
    public function addContent(\CAF\ContentBundle\Entity\Content $contents)
    {
        $this->contents[] = $contents;
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
     * Set fields
     */
    public function setFields(\Doctrine\Common\Collections\Collection $fields)
    {

        foreach ($this->fields as $field) { // On parcours les anciens fields liés
            $field->removeContentTaxonomie($this);   
        }
        
 
        $this->fields = $fields;
        foreach ($this->fields as $field){
            $field->addContentTaxonomie($this);
        }
    }


    public function __toString() {
        return $this->libelle;
    }

    /**
     * Remove contents
     *
     * @param CAF\ContentBundle\Entity\Content $contents
     */
    public function removeContent(\CAF\ContentBundle\Entity\Content $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Add fields
     *
     * @param CAF\ContentBundle\Entity\Fields $fields
     * @return ContentTaxonomy
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

    /**
     * Set published
     *
     * @param boolean $published
     * @return ContentTaxonomy
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
     * @return ContentTaxonomy
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
}