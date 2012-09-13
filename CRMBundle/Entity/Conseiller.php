<?php

namespace CAF\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\CRMBundle\Entity\Conseiller
 *
 * @ORM\Table(name="conseiller")
 * @ORM\Entity
 */
class Conseiller
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $nom;

    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string $numtel
     *
     * @ORM\Column(name="numtel", type="string", length=255)
     */
    private $numtel;

    /**
     * @ORM\OneToOne(targetEntity="CAF\CRMBundle\Entity\Agence")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agence;

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

     public function getNomPrenom()
    {
        return $this->nom.' '.$this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set agence
     *
     * @param CAF\CRMBundle\Entity\Agence $agence
     */
    public function setAgence(\CAF\CRMBundle\Entity\Agence $agence)
    {
        $this->agence = $agence;
    }

    /**
     * Get agence
     *
     * @return CAF\CRMBundle\Entity\Agence 
     */
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set numtel
     *
     * @param string $numtel
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;
    }

    /**
     * Get numtel
     *
     * @return string 
     */
    public function getNumtel()
    {
        return $this->numtel;
    }
}