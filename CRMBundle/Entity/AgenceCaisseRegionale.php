<?php

namespace CAF\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\CRMBundle\Entity\AgenceCaisseRegionale
 *
 * @ORM\Table(name="agencecaisseregionale")
 * @ORM\Entity
 */
class AgenceCaisseRegionale
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
     * @var boolean $published
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

	/**
     * @var string $ville
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string $numero
     *
     * @ORM\Column(name="numero", type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\CaisseRegionale")
     * @ORM\JoinColumn(nullable=false)
     */
    private $caisseRegional;
   


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
     * Set ville
     *
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set numero
     *
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    public function getNumville()
    {
        return $this->numero.' - '.$this->ville;
    }

    /**
     * Set caisseRegional
     *
     * @param CAF\CRMBundle\Entity\CaisseRegionale $caisseRegional
     */
    public function setCaisseRegional(\CAF\CRMBundle\Entity\CaisseRegionale $caisseRegional)
    {
        $this->caisseRegional = $caisseRegional;
    }

    /**
     * Get caisseRegional
     *
     * @return CAF\CRMBundle\Entity\CaisseRegionale 
     */
    public function getCaisseRegional()
    {
        return $this->caisseRegional;
    }
}