<?php

namespace CAF\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\CRMBundle\Entity\HistoEmail
 *
 * @ORM\Table(name="histoemail")
 * @ORM\Entity
 */
class HistoEmail
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
     * @var integer $id_parent
     *
     * @ORM\Column(name="id_parent", type="integer")
     */
    private $id_parent;

     /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\TypeEmail")
     */
    private $typeEmail;

    /**
     * @var string $emailEnvoyeur
     *
     * @ORM\Column(name="emailEnvoyeur", type="string", length=255,nullable=true)
     */
    private $emailEnvoyeur;

    /**
     * @var DateTime $dateEnvoi
     *
     * @ORM\Column(name="dateEnvoi", type="datetime",nullable=true)
     */
    private $dateEnvoi;

    /**
     * @var string $sujet
     *
     * @ORM\Column(name="sujet", type="string", length=255,nullable=true)
     */
    private $sujet;

    /**
     * @var string $emailClient
     *
     * @ORM\Column(name="emailClient", type="string", length=255,nullable=true)
     */
    private $emailClient;
    
    /**
     * @var string $message
     *
     * @ORM\Column(name="message", type="text",nullable=true)
     */
    private $message;
    
    /**
     * @ORM\ManyToOne(targetEntity="CAF\AdminBundle\Entity\User")
     */
    private $user;

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
     * Set id_parent
     *
     * @param integer $idParent
     */
    public function setIdParent($idParent)
    {
        $this->id_parent = $idParent;
    }

    /**
     * Get id_parent
     *
     * @return integer 
     */
    public function getIdParent()
    {
        return $this->id_parent;
    }

    /**
     * Set dateEnvoi
     *
     * @param date $dateEnvoi
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    }

    /**
     * Get dateEnvoi
     *
     * @return date 
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set sujet
     *
     * @param string $sujet
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }

    /**
     * Get sujet
     *
     * @return string 
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set emailClient
     *
     * @param string $emailClient
     */
    public function setEmailClient($emailClient)
    {
        $this->emailClient = $emailClient;
    }

    /**
     * Get emailClient
     *
     * @return string 
     */
    public function getEmailClient()
    {
        return $this->emailClient;
    }

    /**
     * Set message
     *
     * @param text $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set typeEmail
     *
     * @param CAF\CRMBundle\Entity\TypeEmail $typeEmail
     */
    public function setTypeEmail(\CAF\CRMBundle\Entity\TypeEmail $typeEmail)
    {
        $this->typeEmail = $typeEmail;
    }

    /**
     * Get typeEmail
     *
     * @return CAF\CRMBundle\Entity\TypeEmail 
     */
    public function getTypeEmail()
    {
        return $this->typeEmail;
    }

    

    /**
     * Set emailEnvoyeur
     *
     * @param string $emailEnvoyeur
     */
    public function setEmailEnvoyeur($emailEnvoyeur)
    {
        $this->emailEnvoyeur = $emailEnvoyeur;
    }

    /**
     * Get emailEnvoyeur
     *
     * @return string 
     */
    public function getEmailEnvoyeur()
    {
        return $this->emailEnvoyeur;
    }

    public function cloneTypeEmail($currentTypeEmail){
        $newTypeEmail = new HistoEmail();
        $now = date('Y-m-d G:i:s');
        $newTypeEmail->setEmailEnvoyeur($currentTypeEmail->getEmailEnvoyeur());              
        $newTypeEmail->setDateEnvoi(new \DateTime($now));

         if($currentTypeEmail->getIdParent()){
            $idparent = $currentTypeEmail->getIdParent();
        }
        else{
            $idparent = $currentTypeEmail->getId();
        } 
        $newTypeEmail->setIdParent($idparent);
        $newTypeEmail->setSujet($currentTypeEmail->getSujet());
        $newTypeEmail->setEmailClient($currentTypeEmail->getEmailClient());
        $newTypeEmail->setMessage($currentTypeEmail->getMessage());
        $newTypeEmail->setTypeEmail($currentTypeEmail->getTypeEmail());
        $newTypeEmail->setUser($this->getUser());   
        return $newTypeEmail;
    }

    /**
     * Set user
     *
     * @param CAF\AdminBundle\Entity\User $user
     * @return HistoEmail
     */
    public function setUser(\CAF\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return CAF\AdminBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}