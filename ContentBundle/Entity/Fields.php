<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\Fields
 *
 * @ORM\Table(name="fields")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\FieldsRepository")
 */
class Fields
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
     * @var integer $id_field_taxonomy
     *
     * @ORM\ManyToOne(targetEntity="FieldTaxonomy")
     * @ORM\JoinColumn(name="id_field_taxonomy", referencedColumnName="id")
     */
    private $id_field_taxonomy;

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
     * @ORM\ManyToMany(targetEntity="ContentTaxonomy", inversedBy="fields")
     * @ORM\JoinTable(name="fields_content_taxonomy")
     */
    private $content_taxonomies;

    /**
     * @ORM\OneToMany(targetEntity="FieldsValue", mappedBy="field", cascade={"remove", "persist"})
     * @ORM\OrderBy({"ordre" = "ASC"})
     */
    private $fieldsvalue;



    public function __toString()
    {
        return $this->name;
    }


    public function __construct()
    {
        $this->content_taxonomies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id_field_taxonomy
     *
     * @param CAF\ContentBundle\Entity\FieldTaxonomy $idFieldTaxonomy
     */
    public function setIdFieldTaxonomy(\CAF\ContentBundle\Entity\FieldTaxonomy $idFieldTaxonomy)
    {
        $this->id_field_taxonomy = $idFieldTaxonomy;
    }

    /**
     * Get id_field_taxonomy
     *
     * @return CAF\ContentBundle\Entity\FieldTaxonomy 
     */
    public function getIdFieldTaxonomy()
    {
        return $this->id_field_taxonomy;
    }


    /**
     * Get content_taxonomies
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContentTaxonomies()
    {
        return $this->content_taxonomies;
    }


    /**
     * Get fieldsvalue
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFieldsvalue()
    {
        return $this->fieldsvalue;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
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
     */
    public function setRequired($required)
    {
        $this->required = $required;
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
     * Set format
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Get format
     *
     * @return string 
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Add content_taxonomies
     *
     * @param CAF\ContentBundle\Entity\ContentTaxonomy $contentTaxonomies
     * @return Fields
     */
    public function addContentTaxonomie(\CAF\ContentBundle\Entity\ContentTaxonomy $contentTaxonomies)
    {
        $this->content_taxonomies[] = $contentTaxonomies;
        $contentTaxonomies->addField($this);
        return $this;
    }

    /**
     * Remove content_taxonomies
     *
     * @param CAF\ContentBundle\Entity\ContentTaxonomy $contentTaxonomies
     */
    public function removeContentTaxonomie(\CAF\ContentBundle\Entity\ContentTaxonomy $contentTaxonomies)
    {
        $this->content_taxonomies->removeElement($contentTaxonomies);
    }

    /**
     * Add fieldsvalue
     *
     * @param CAF\ContentBundle\Entity\FieldsValue $fieldsvalue
     * @return Fields
     */
    public function addFieldsvalue(\CAF\ContentBundle\Entity\FieldsValue $fieldsvalue)
    {
        $this->fieldsvalue[] = $fieldsvalue;
    
        return $this;
    }

    /**
     * Remove fieldsvalue
     *
     * @param CAF\ContentBundle\Entity\FieldsValue $fieldsvalue
     */
    public function removeFieldsvalue(\CAF\ContentBundle\Entity\FieldsValue $fieldsvalue)
    {
        $this->fieldsvalue->removeElement($fieldsvalue);
    }
}