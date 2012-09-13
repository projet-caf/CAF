<?php

namespace CAF\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categories_ext")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\CategoryRepository")
 */
class Category
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
     * @var int $ordre
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;

    
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;


    /**
      * @ORM\OneToMany(targetEntity="CategoryTranslation", mappedBy="category", cascade={"remove", "persist"})
      */
    private $translations;

    /**
     * @ORM\Column(name="template", type="string", length=255)
     */
    private $template;

     

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    
    public function __toString() {
       
        return str_repeat('-', $this->lvl).' '.$this->translations[0]->getTitle();
    }

    /**
     * Set lft
     *
     * @param integer $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
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
     * Set metasValuesFr
     *
     * @return array 
     */
    public function setMetasValuesFr(array $metasValuesFr)
    {
        $this->metasValuesFr = $metasValuesFr;
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
     * Set metasValuesDe
     *
     * @return array 
     */
    public function setMetasValuesDe(array $metasValuesDe)
    {
        $this->metasValuesDe = $metasValuesDe;
    }
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add children
     *
     * @param CAF\MenuBundle\Entity\Menu $children
     */
    public function addMenu(\CAF\MenuBundle\Entity\Menu $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add translations
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $translations
     */
    public function addCategoryTranslation(\CAF\ContentBundle\Entity\CategoryTranslation $translations)
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
    public function setTranslationFr(CategoryTranslation $translationFr)
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
    public function setTranslationEn(CategoryTranslation $translationEn)
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
    public function setTranslationDe(CategoryTranslation $translationDe)
    {
        $this->translationDe = $translationDe;
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
     * Add children
     *
     * @param CAF\ContentBundle\Entity\Category $children
     */
    public function addCategory(\CAF\ContentBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    }

    /**
     * Add contents
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contents
     */
    public function addContentTranslation(\CAF\ContentBundle\Entity\ContentTranslation $contents)
    {
        $this->contents[] = $contents;
    }

    /**
     * Add children
     *
     * @param CAF\ContentBundle\Entity\Category $children
     * @return Category
     */
    public function addChildren(\CAF\ContentBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param CAF\ContentBundle\Entity\Category $children
     */
    public function removeChildren(\CAF\ContentBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Add translations
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $translations
     * @return Category
     */
    public function addTranslation(\CAF\ContentBundle\Entity\CategoryTranslation $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $translations
     */
    public function removeTranslation(\CAF\ContentBundle\Entity\CategoryTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    
        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Category
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