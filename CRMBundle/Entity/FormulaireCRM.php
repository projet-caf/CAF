<?php

namespace CAF\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\CRMBundle\Entity\FormulaireCRM
 *
 * @ORM\Table(name="formulairecrm")
 * @ORM\Entity
 */
class FormulaireCRM
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
     * @var string $nomform
     *
     * @ORM\Column(name="nomform", type="string")
     */
    private $nomform;


    /**
     * @ORM\OneToOne(targetEntity="CAF\CRMBundle\Entity\HistoStatut")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currentStatut;

    /**
     * @ORM\OneToOne(targetEntity="CAF\CRMBundle\Entity\HistoEmail")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currentTypeEmail;

   
    

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
     * Set nomform
     *
     * @param string $nomform
     */
    public function setNomform($nomform)
    {
        $this->nomform = $nomform;
    }

    /**
     * Get nomform
     *
     * @return string 
     */
    public function getNomform()
    {
        return $this->nomform;
    }

    /**
     * Set currentStatut
     *
     * @param CAF\CRMBundle\Entity\HistoStatut $currentStatut
     */
    public function setCurrentStatut(\CAF\CRMBundle\Entity\HistoStatut $currentStatut)
    {
        $this->currentStatut = $currentStatut;
    }

    /**
     * Get currentStatut
     *
     * @return CAF\CRMBundle\Entity\HistoStatut 
     */
    public function getCurrentStatut()
    {
        return $this->currentStatut;
    }

    /**
     * Set currentTypeEmail
     *
     * @param CAF\CRMBundle\Entity\HistoEmail $currentTypeEmail
     */
    public function setCurrentTypeEmail(\CAF\CRMBundle\Entity\HistoEmail $currentTypeEmail)
    {
        $this->currentTypeEmail = $currentTypeEmail;
    }

    /**
     * Get currentTypeEmail
     *
     * @return CAF\CRMBundle\Entity\HistoEmail 
     */
    public function getCurrentTypeEmail()
    {
        return $this->currentTypeEmail;
    }
}