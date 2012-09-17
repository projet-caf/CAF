<?php

namespace CAF\BlocBundle\Entity;

use CAF\ContentBundle\Entity\Category;
use CAF\ContentBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\BlocBundle\Entity\Bloc
 *
 * @ORM\Table("bloc")
 * @ORM\Entity(repositoryClass="CAF\BlocBundle\Entity\Repository\BlocRepository")
 */
class Bloc
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var boolean $display_title
     *
     * @ORM\Column(name="display_title", type="boolean", length=255, nullable=true)
     */
    private $display_title;

    /**
     * @var string $position
     *
     * @ORM\Column(name="position", type="string", length=255)
     */
    private $position;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string $params
     *
     * @ORM\Column(name="params", type="string", length=255, nullable=true)
     */
    private $params;

    /**
     * @var string $ordre
     *
     * @ORM\Column(name="ordre_bloc", type="string", length=255, nullable=true)
     */
    private $ordre;


    /**
     * @var boolean $published
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var boolean $all_published
     *
     * @ORM\Column(name="all_published", type="boolean", length=255, nullable=true)
     */
    private $all_published;

    /**
     * @ORM\ManyToMany(targetEntity="\CAF\ContentBundle\Entity\CategoryTranslation")
     * @ORM\JoinTable(name="bloc_category_fr")
     */
    private $categories_fr;

    /**
     * @ORM\ManyToMany(targetEntity="\CAF\ContentBundle\Entity\ContentTranslation")
     * @ORM\JoinTable(name="bloc_content_fr")
     */
    private $contents_fr;

    /**
     * @ORM\ManyToMany(targetEntity="\CAF\ContentBundle\Entity\CategoryTranslation")
     * @ORM\JoinTable(name="bloc_category_en")
     */
    private $categories_en;

    /**
     * @ORM\ManyToMany(targetEntity="\CAF\ContentBundle\Entity\ContentTranslation")
     * @ORM\JoinTable(name="bloc_content_en")
     */
    private $contents_en;

    /**
     * @ORM\ManyToMany(targetEntity="\CAF\ContentBundle\Entity\CategoryTranslation")
     * @ORM\JoinTable(name="bloc_category_de")
     */
    private $categories_de;

    /**
     * @ORM\ManyToMany(targetEntity="\CAF\ContentBundle\Entity\ContentTranslation")
     * @ORM\JoinTable(name="bloc_content_de")
     */
    private $contents_de;

    /**
     * @var string $html
     */
    public $html;

    public function __construct(){
        $this->all_published = 1;
        $this->display_title = 0;
        $this->published = 0;
        return $this;
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

    public function getHtml()
    {
        return $this->html;
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

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
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
     * Add bloc_menu
     *
     * @param  $bloc
     */
    public function addBloc($bloc)
    {
        $this->blocs[] = $bloc;
    }

    /**
     * Get bloc_menu
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBlocs()
    {
        return $this->blocs;
    }

    /**
     * Set position
     *
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }


    /**
     * Set params
     *
     * @param string $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
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
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add contents
     *
     * @param CAF\ContentBundle\Entity\Content $contents
     */
    public function addContent(\CAF\ContentBundle\Entity\Content $contents)
    {
        $this->contents[] = $contents;
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
     * Add categories
     *
     * @param CAF\ContentBundle\Entity\Category $categories
     * @return Bloc
     */
    public function addCategorie(\CAF\ContentBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Set categories
     *
     * @param $categories
     * @return Bloc
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param CAF\ContentBundle\Entity\Category $categories
     */
    public function removeCategorie(\CAF\ContentBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Remove contents
     *
     * @param CAF\ContentBundle\Entity\Content $contents
     */
    public function removeContent(\CAF\ContentBundle\Entity\Content $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Set all_published
     *
     * @param boolean $allPublished
     * @return Bloc
     */
    public function setAllPublished($allPublished)
    {
        $this->all_published = $allPublished;
    
        return $this;
    }

    /**
     * Get all_published
     *
     * @return boolean 
     */
    public function getAllPublished()
    {
        return $this->all_published;
    }

    /**
     * Set ordre
     *
     * @param string $ordre
     * @return Bloc
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    
        return $this;
    }

    /**
     * Get ordre
     *
     * @return string 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set display_title
     *
     * @param boolean $displayTitle
     * @return Bloc
     */
    public function setDisplayTitle($displayTitle)
    {
        $this->display_title = $displayTitle;
    
        return $this;
    }

    /**
     * Get display_title
     *
     * @return boolean 
     */
    public function getDisplayTitle()
    {
        return $this->display_title;
    }

    /**
     * Add categories_fr
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoriesFr
     * @return Bloc
     */
    public function addCategoriesFr(\CAF\ContentBundle\Entity\CategoryTranslation $categoriesFr)
    {
        $this->categories_fr[] = $categoriesFr;
    
        return $this;
    }

    /**
     * Remove categories_fr
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoriesFr
     */
    public function removeCategoriesFr(\CAF\ContentBundle\Entity\CategoryTranslation $categoriesFr)
    {
        $this->categories_fr->removeElement($categoriesFr);
    }

    /**
     * Get categories_fr
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoriesFr()
    {
        return $this->categories_fr;
    }

    /**
     * Add contents_fr
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentsFr
     * @return Bloc
     */
    public function addContentsFr(\CAF\ContentBundle\Entity\ContentTranslation $contentsFr)
    {
        $this->contents_fr[] = $contentsFr;
    
        return $this;
    }

    /**
     * Remove contents_fr
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentsFr
     */
    public function removeContentsFr(\CAF\ContentBundle\Entity\ContentTranslation $contentsFr)
    {
        $this->contents_fr->removeElement($contentsFr);
    }

    /**
     * Get contents_fr
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContentsFr()
    {
        return $this->contents_fr;
    }

    /**
     * Add categories_en
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoriesEn
     * @return Bloc
     */
    public function addCategoriesEn(\CAF\ContentBundle\Entity\CategoryTranslation $categoriesEn)
    {
        $this->categories_en[] = $categoriesEn;
    
        return $this;
    }

    /**
     * Remove categories_en
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoriesEn
     */
    public function removeCategoriesEn(\CAF\ContentBundle\Entity\CategoryTranslation $categoriesEn)
    {
        $this->categories_en->removeElement($categoriesEn);
    }

    /**
     * Get categories_en
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoriesEn()
    {
        return $this->categories_en;
    }

    /**
     * Add contents_en
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentsEn
     * @return Bloc
     */
    public function addContentsEn(\CAF\ContentBundle\Entity\ContentTranslation $contentsEn)
    {
        $this->contents_en[] = $contentsEn;
    
        return $this;
    }

    /**
     * Remove contents_en
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentsEn
     */
    public function removeContentsEn(\CAF\ContentBundle\Entity\ContentTranslation $contentsEn)
    {
        $this->contents_en->removeElement($contentsEn);
    }

    /**
     * Get contents_en
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContentsEn()
    {
        return $this->contents_en;
    }

    /**
     * Add categories_de
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoriesDe
     * @return Bloc
     */
    public function addCategoriesDe(\CAF\ContentBundle\Entity\CategoryTranslation $categoriesDe)
    {
        $this->categories_de[] = $categoriesDe;
    
        return $this;
    }

    /**
     * Remove categories_de
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoriesDe
     */
    public function removeCategoriesDe(\CAF\ContentBundle\Entity\CategoryTranslation $categoriesDe)
    {
        $this->categories_de->removeElement($categoriesDe);
    }

    /**
     * Get categories_de
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoriesDe()
    {
        return $this->categories_de;
    }

    /**
     * Add contents_de
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentsDe
     * @return Bloc
     */
    public function addContentsDe(\CAF\ContentBundle\Entity\ContentTranslation $contentsDe)
    {
        $this->contents_de[] = $contentsDe;
    
        return $this;
    }

    /**
     * Remove contents_de
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentsDe
     */
    public function removeContentsDe(\CAF\ContentBundle\Entity\ContentTranslation $contentsDe)
    {
        $this->contents_de->removeElement($contentsDe);
    }

    /**
     * Get contents_de
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContentsDe()
    {
        return $this->contents_de;
    }
}