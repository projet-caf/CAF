<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\ContentRepository")
 */
class Content
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
     * @var integer $id_content_taxonomy
     *
     * @ORM\ManyToOne(targetEntity="ContentTaxonomy", inversedBy="contents")
     * @ORM\JoinColumn(name="id_content_taxonomy", referencedColumnName="id")
     */
    private $id_content_taxonomy;

    /**
     * @ORM\OneToMany(targetEntity="Metas", mappedBy="id_content")
     **/
    private $metas;

    /**
      * @ORM\OneToMany(targetEntity="ContentTranslation", mappedBy="content", cascade={"remove", "persist"})
      */
    private $translations;


    public $valuesFr;
    public $valuesEn;
    public $valuesDe;


    public $metasValuesFr;
    public $metasValuesEn;
    public $metasValuesDe;

    public $translationFr;
    public $translationEn;
    public $translationDe;

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
     * Set id_content_type
     *
     * @param integer $idContentType
     */
    public function setIdContentType($idContentType)
    {
        $this->id_content_type = $idContentType;
    }


    public function __construct()
    {
        $this->metas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->valuesFr = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->translations[0]->getTitle();
    }
    
    /**
     * Set id_content_taxonomy
     *
     * @param CAF\ContentBundle\Entity\ContentTaxonomy $idContentTaxonomy
     */
    public function setIdContentTaxonomy(\CAF\ContentBundle\Entity\ContentTaxonomy $idContentTaxonomy)
    {
        $this->id_content_taxonomy = $idContentTaxonomy;
    }

    /**
     * Get id_content_taxonomy
     *
     * @return CAF\ContentBundle\Entity\ContentTaxonomy 
     */
    public function getIdContentTaxonomy()
    {
        return $this->id_content_taxonomy;
    }

    /**
     * Add metas
     *
     * @param CAF\ContentBundle\Entity\Metas $metas
     */
    public function addMetas(\CAF\ContentBundle\Entity\Metas $metas)
    {
        $this->metas[] = $metas;
    }

    /**
     * Get metas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetas()
    {
        return $this->metas;
    }

    

    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }


    /**
     * Add translations
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $translations
     */
    public function addContentTranslation(\CAF\ContentBundle\Entity\ContentTranslation $translations)
    {
        $this->translations[] = $translations;
    }

    /**
     * Get translations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set translations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
    }

    /**
     * Get valuesFr
     *
     * @return array 
     */
    public function getValuesFr()
    {
        return $this->valuesFr;
    }


    /**
     * Set valuesFr
     *
     * @return array 
     */
    public function setValuesFr(array $valuesFr)
    {
        $this->valuesFr = $valuesFr;
    }


    /**
     * Get valuesEn
     *
     * @return array 
     */
    public function getValuesEn()
    {
        return $this->valuesEn;
    }

    /**
     * Set valuesEn
     *
     * @return array 
     */
    public function setValuesEn(array $valuesEn)
    {
        $this->valuesEn = $valuesEn;
    }

    /**
     * Get valuesDe
     *
     * @return array 
     */
    public function getValuesDe()
    {
        return $this->valuesDe;
    }

    /**
     * Set valuesDe
     *
     * @return array 
     */
    public function setValuesDe(array $valuesDe)
    {
        $this->valuesDe = $valuesDe;
    }

    /**
     * Get metasValuesFr
     *
     * @return array 
     */
    public function getMetasValuesFr()
    {
        return $this->metasValuesFr;
    }

    /**
     * Set metasValuesFr
     *
     * @return array 
     */
    public function setMetasValuesFr(array $metasValuesFr)
    {
        $this->metasValuesFr = $metasValuesFr;
    }


    /**
     * Get valuesEn
     *
     * @return array 
     */
    public function getMetasValuesEn()
    {
        return $this->metasValuesEn;
    }

    /**
     * Set metasValuesEn
     *
     * @return array 
     */
    public function setMetasValuesEn(array $metasValuesEn)
    {
        $this->metasValuesEn = $metasValuesEn;
    }

    /**
     * Get metasValuesDe
     *
     * @return array 
     */
    public function getMetasValuesDe()
    {
        return $this->metasValuesDe;
    }

    /**
     * Set metasValuesDe
     *
     * @return array 
     */
    public function setMetasValuesDe(array $metasValuesDe)
    {
        $this->metasValuesDe = $metasValuesDe;
    }

    /**
     * Get translationFr
     *
     * @return ContentTranslation 
     */
    public function getTranslationFr()
    {
        return $this->translationFr;
    }

    /**
     * Set translationFr
     *
     * @return array 
     */
    public function setTranslationFr(ContentTranslation $translationFr)
    {
        $this->translationFr = $translationFr;
    }

    /**
     * Get translationEn
     *
     * @return ContentTranslation 
     */
    public function getTranslationEn()
    {
        return $this->translationEn;
    }

    /**
     * Set translationEn
     *
     * @return array 
     */
    public function setTranslationEn(ContentTranslation $translationEn)
    {
        $this->translationEn = $translationEn;
    }

    /**
     * Get translationDe
     *
     * @return ContentTranslation 
     */
    public function getTranslationDe()
    {
        return $this->translationDe;
    }

    /**
     * Set translationDe
     *
     * @return array 
     */
    public function setTranslationDe(ContentTranslation $translationDe)
    {
        $this->translationDe = $translationDe;
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
     * Add metas
     *
     * @param CAF\ContentBundle\Entity\Metas $metas
     * @return Content
     */
    public function addMeta(\CAF\ContentBundle\Entity\Metas $metas)
    {
        $this->metas[] = $metas;
    
        return $this;
    }

    /**
     * Remove metas
     *
     * @param CAF\ContentBundle\Entity\Metas $metas
     */
    public function removeMeta(\CAF\ContentBundle\Entity\Metas $metas)
    {
        $this->metas->removeElement($metas);
    }

    /**
     * Add translations
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $translations
     * @return Content
     */
    public function addTranslation(\CAF\ContentBundle\Entity\ContentTranslation $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $translations
     */
    public function removeTranslation(\CAF\ContentBundle\Entity\ContentTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }
}