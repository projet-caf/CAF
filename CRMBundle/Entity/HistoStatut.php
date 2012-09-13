<?php

namespace CAF\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\CRMBundle\Entity\HistoStatut
 *
 * @ORM\Table(name="histostatut")
 * @ORM\Entity
 */
class HistoStatut
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
     * @var DateTime $dateStatutMAJ
     *
     * @ORM\Column(name="dateStatutMAJ", type="datetime")
     */
    private $dateStatutMAJ;

    /**
     * @var integer $id_parent
     *
     * @ORM\Column(name="id_parent", type="integer")
     */
    private $id_parent;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\StatutDemande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statutDemande;

     /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\Agence")
     */
    private $agence;

	/**
     * @var DateTime $daterdv
     *
     * @ORM\Column(name="daterdv", type="datetime",nullable=true)
     */
    private $daterdv;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\Conseiller")
     */
    private $conseiller;

    /**
     * @var DateTime $dateEnvoi
     *
     * @ORM\Column(name="dateEnvoi", type="datetime",nullable=true)
     */
    private $dateEnvoi;
    
    /**
     * @var string $racineCompte
     *
     * @ORM\Column(name="racineCompte", type="string",nullable=true)
     */
    private $racineCompte;

   /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\CaisseRegionale")
     */
    private $caisseRegionale;

     /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\AgenceCaisseRegionale")
     */
    private $agenceCaisseRegionale;

     /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\ProvenanceDemande")
     */
    private $provenanceDemande;


     /**
     * @ORM\ManyToOne(targetEntity="CAF\CRMBundle\Entity\TypeRecommandation")
     */
    private $typeRecommandation;

     /**
     * @var integer $numeroRecommandation
     * @ORM\Column(name="numeroRecommandation", type="integer",nullable=true)
     */
    private $numeroRecommandation;

    /**
     * @var string $commentaire
     *
     * @ORM\Column(name="commentaire", type="text",nullable=true)
     */
    private $commentaire;

  
     /**
     * @ORM\ManyToOne(targetEntity="CAF\AdminBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $langue;
    
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
     * Set id
     *
     * @return integer 
     */
    public function setId()
    {
        $this->id=15;
    }

    /**
     * Set daterdv
     *
     * @param date $daterdv
     */
    public function setDaterdv($daterdv)
    {
        $this->daterdv = $daterdv;
    }

    /**
     * Get daterdv
     *
     * @return date 
     */
    public function getDaterdv()
    {
        return $this->daterdv;
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
     * Set racineCompte
     *
     * @param string $racineCompte
     */
    public function setRacineCompte($racineCompte)
    {
        $this->racineCompte = $racineCompte;
    }

    /**
     * Get racineCompte
     *
     * @return string 
     */
    public function getRacineCompte()
    {
        return $this->racineCompte;
    }

    /**
     * Set numeroRecommandation
     *
     * @param integer $numeroRecommandation
     */
    public function setNumeroRecommandation($numeroRecommandation)
    {
        $this->numeroRecommandation = $numeroRecommandation;
    }

    /**
     * Get numeroRecommandation
     *
     * @return integer 
     */
    public function getNumeroRecommandation()
    {
        return $this->numeroRecommandation;
    }

    /**
     * Set commentaire
     *
     * @param text $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Get commentaire
     *
     * @return text 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
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
     * Set conseiller
     *
     * @param CAF\CRMBundle\Entity\Conseiller $conseiller
     */
    public function setConseiller(\CAF\CRMBundle\Entity\Conseiller $conseiller)
    {
        $this->conseiller = $conseiller;
    }

    /**
     * Get conseiller
     *
     * @return CAF\CRMBundle\Entity\Conseiller 
     */
    public function getConseiller()
    {
        return $this->conseiller;
    }

    /**
     * Set caisseRegionale
     *
     * @param CAF\CRMBundle\Entity\CaisseRegionale $caisseRegionale
     */
    public function setCaisseRegionale(\CAF\CRMBundle\Entity\CaisseRegionale $caisseRegionale)
    {
        $this->caisseRegionale = $caisseRegionale;
    }

    /**
     * Get caisseRegionale
     *
     * @return CAF\CRMBundle\Entity\CaisseRegionale 
     */
    public function getCaisseRegionale()
    {
        return $this->caisseRegionale;
    }

    /**
     * Set agenceCaisseRegionale
     *
     * @param CAF\CRMBundle\Entity\AgenceCaisseRegionale $agenceCaisseRegionale
     */
    public function setAgenceCaisseRegionale(\CAF\CRMBundle\Entity\AgenceCaisseRegionale $agenceCaisseRegionale)
    {
        $this->agenceCaisseRegionale = $agenceCaisseRegionale;
    }

    /**
     * Get agenceCaisseRegionale
     *
     * @return CAF\CRMBundle\Entity\AgenceCaisseRegionale 
     */
    public function getAgenceCaisseRegionale()
    {
        return $this->agenceCaisseRegionale;
    }

    /**
     * Set langue
     *
     * @param CAF\AdminBundle\Entity\Language $langue
     */
    public function setLangue(\CAF\AdminBundle\Entity\Language $langue)
    {
        $this->langue = $langue;
    }

    /**
     * Get langue
     *
     * @return CAF\AdminBundle\Entity\Language 
     */
    public function getLangue()
    {
        return $this->langue;
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
     * Set dateStatutMAJ
     *
     * @param date $dateStatutMAJ
     */
    public function setDateStatutMAJ($dateStatutMAJ)
    {
        $this->dateStatutMAJ = $dateStatutMAJ;
    }

    /**
     * Get dateStatutMAJ
     *
     * @return date 
     */
    public function getDateStatutMAJ()
    {
        return $this->dateStatutMAJ;
    }


    /**
     * Set statutDemande
     *
     * @param CAF\CRMBundle\Entity\StatutDemande $statutDemande
     */
    public function setStatutDemande(\CAF\CRMBundle\Entity\StatutDemande $statutDemande)
    {
        $this->statutDemande = $statutDemande;
    }

    /**
     * Get statutDemande
     *
     * @return CAF\CRMBundle\Entity\StatutDemande 
     */
    public function getStatutDemande()
    {
        return $this->statutDemande;
    }


    public function getNomStatutDemande(){
        return $this->statutDemande->getLibelle();
    }


    public function cloneStatut($currentStatut){
        $newStatut = new HistoStatut();
        $now = date('Y-m-d G:i:s');
        $newStatut->setAgence($currentStatut->getAgence());
        $newStatut->setConseiller($currentStatut->getConseiller());
        $newStatut->setLangue($currentStatut->getLangue());
        if($currentStatut->getIdParent()){
            $idparent = $currentStatut->getIdParent();
        }
        else{
            $idparent = $currentStatut->getId();
        }
        $newStatut->setIdParent($idparent);
        $newStatut->setDaterdv($currentStatut->getDaterdv());
        $newStatut->setDateEnvoi($currentStatut->getDateEnvoi());
        $newStatut->setRacineCompte($currentStatut->getRacineCompte());
        $newStatut->setNumeroRecommandation($currentStatut->getNumeroRecommandation());
        $newStatut->setCommentaire($currentStatut->getCommentaire());
        $newStatut->setCaisseRegionale($currentStatut->getCaisseRegionale());
        $newStatut->setDateStatutMAJ($currentStatut->getDateStatutMAJ());
        $newStatut->setAgenceCaisseRegionale($currentStatut->getAgenceCaisseRegionale());
        $newStatut->setDateStatutMAJ(new \DateTime($now));
        $newStatut->setStatutDemande($currentStatut->getStatutDemande());
        $newStatut->setTypeRecommandation($currentStatut->getTypeRecommandation());
        $newStatut->setProvenanceDemande($currentStatut->getProvenanceDemande());
        $newStatut->setUser($this->getUser());
    
        return $newStatut;
    }

    /**
     * Set provenanceDemande
     *
     * @param CAF\CRMBundle\Entity\ProvenanceDemande $provenanceDemande
     * @return HistoStatut
     */
    public function setProvenanceDemande(\CAF\CRMBundle\Entity\ProvenanceDemande $provenanceDemande = null)
    {
        $this->provenanceDemande = $provenanceDemande;
    
        return $this;
    }

    /**
     * Get provenanceDemande
     *
     * @return CAF\CRMBundle\Entity\ProvenanceDemande 
     */
    public function getProvenanceDemande()
    {
        return $this->provenanceDemande;
    }

    /**
     * Set typeRecommandation
     *
     * @param CAF\CRMBundle\Entity\TypeRecommandation $typeRecommandation
     * @return HistoStatut
     */
    public function setTypeRecommandation(\CAF\CRMBundle\Entity\TypeRecommandation $typeRecommandation = null)
    {
        $this->typeRecommandation = $typeRecommandation;
    
        return $this;
    }

    /**
     * Get typeRecommandation
     *
     * @return CAF\CRMBundle\Entity\TypeRecommandation 
     */
    public function getTypeRecommandation()
    {
        return $this->typeRecommandation;
    }

    /**
     * Set user
     *
     * @param CAF\AdminBundle\Entity\User $user
     * @return HistoStatut
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