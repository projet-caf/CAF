<?php

namespace CAF\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\CRMBundle\Entity\CaisseRegionale
 *
 * @ORM\Table(name="caisseregionale")
 * @ORM\Entity
 */
class CaisseRegionale
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
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string $numero
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;




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
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
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


    public function getNumlabel()
    {
        return $this->numero .' - '. $this->label ;
    }

}