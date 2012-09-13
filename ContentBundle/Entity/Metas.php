<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\Metas
 *
 * @ORM\Table(name="metas")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\MetasRepository")
 */
class Metas
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string $balise
     *
     * @ORM\Column(name="balise", type="string", length=150)
     */
    private $balise;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var string $display
     *
     * @ORM\Column(name="display", type="string", length=50)
     */
    private $display;

    /**
     * @ORM\OneToMany(targetEntity="MetasValue", mappedBy="meta", cascade={"remove", "persist"})
     */
    private $metasvalues;

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

    /**
     * Set params
     *
     * @param text $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Get params
     *
     * @return text 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set id_content
     *
     * @param CAF\ContentBundle\Entity\Content $idContent
     */
    public function setIdContent(\CAF\ContentBundle\Entity\Content $idContent)
    {
        $this->id_content = $idContent;
    }

    /**
     * Get id_content
     *
     * @return CAF\ContentBundle\Entity\Content 
     */
    public function getIdContent()
    {
        return $this->id_content;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
    public function __construct()
    {
        $this->metasvalues = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get metasvalues
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetasvalues()
    {
        return $this->metasvalues;
    }

    /**
     * Set display
     *
     * @param string $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * Get display
     *
     * @return string 
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Add metasvalues
     *
     * @param CAF\ContentBundle\Entity\MetasValue $metasvalues
     * @return Metas
     */
    public function addMetasvalue(\CAF\ContentBundle\Entity\MetasValue $metasvalues)
    {
        $this->metasvalues[] = $metasvalues;
    
        return $this;
    }

    /**
     * Remove metasvalues
     *
     * @param CAF\ContentBundle\Entity\MetasValue $metasvalues
     */
    public function removeMetasvalue(\CAF\ContentBundle\Entity\MetasValue $metasvalues)
    {
        $this->metasvalues->removeElement($metasvalues);
    }
}