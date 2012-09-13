<?php
namespace CAF\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="menu_ext")
 * @ORM\Entity(repositoryClass="CAF\MenuBundle\Entity\Repository\MenuRepository")
 * @Gedmo\TranslationEntity(class="CAF\MenuBundle\Entity\MenuTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class Menu implements Translatable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="published", type="boolean")
     */
    private $published=0;

    /**
     * @ORM\ManyToMany(targetEntity="CAF\AdminBundle\Entity\Country", inversedBy="menus", cascade={"persist"})
     * @ORM\JoinTable(name="menu_country_ext")
     */
    private $countries;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="link_taxonomy", type="boolean")
     */
    private $link_taxonomy;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="urls", type="string", nullable=true)
     */
    private $urls;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="urls_content", type="string", nullable=true)
     */
    private $urls_content;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\ContentBundle\Entity\CategoryTranslation", inversedBy="menus")
     * @ORM\JoinColumn(name="category", nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="CAF\ContentBundle\Entity\ContentTranslation", inversedBy="menus")
     * @ORM\JoinColumn(name="content", nullable=true)
     */
    private $content;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @var entity ordre
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     */
    private $children;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(
     *   targetEntity="MenuTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="MenuTaxonomy", inversedBy="menus")
     * @ORM\JoinColumn(name="id_menu_taxonomy", referencedColumnName="id")
     */
    protected $id_menu_taxonomy;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var array media
     * @Gedmo\Translatable 
     * @ORM\Column(name="media", type="array", nullable=true)
     */
    private $media;

    /**
     * @ORM\Column(name="menu_picto", type="boolean")
     */
    private $menu_picto;

    /**
     * @ORM\Column(name="path", type="string", nullable=true)
     */
    private $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @var array myCategory
     */
    private $myCategory;   
      
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(MenuTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function getMyCategory()
    {
        return $this->myCategory;
    }    
    
    public function setMyCategory(array $myCategory)
    {
        $this->myCategory = $myCategory;
    }    
    
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getLeft()
    {
        return $this->lft;
    }

    public function getRight()
    {
        return $this->rgt;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Menu
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Menu
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    
        return $this;
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
     * @return Menu
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    
        return $this;
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
     * Set root
     *
     * @param integer $root
     * @return Menu
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Menu
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Menu
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Menu
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Add children
     *
     * @param CAF\MenuBundle\Entity\Menu $children
     * @return Menu
     */
    public function addChildren(\CAF\MenuBundle\Entity\Menu $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param CAF\MenuBundle\Entity\Menu $children
     */
    public function removeChildren(\CAF\MenuBundle\Entity\Menu $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Remove translations
     *
     * @param CAF\MenuBundle\Entity\MenuTranslation $translations
     */
    public function removeTranslation(\CAF\MenuBundle\Entity\MenuTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Set id_menu_taxonomy
     *
     * @param CAF\MenuBundle\Entity\MenuTaxonomy $idMenuTaxonomy
     * @return Menu
     */
    public function setIdMenuTaxonomy(\CAF\MenuBundle\Entity\MenuTaxonomy $idMenuTaxonomy = null)
    {
        $this->id_menu_taxonomy = $idMenuTaxonomy;
    
        return $this;
    }

    /**
     * Get id_menu_taxonomy
     *
     * @return CAF\MenuBundle\Entity\MenuTaxonomy 
     */
    public function getIdMenuTaxonomy()
    {
        return $this->id_menu_taxonomy;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {   
        if (null != $this->media['image']) {
            // do whatever you want to generate a unique name
            $this->path = uniqid().'.'.$this->media['image']->guessExtension();
            $this->file = $this->media['image'];
            $this->media['image'] = $this->getUploadDir().$this->path;
        }
        else {
            $this->media['image'] = '';
        }

    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set path
     *
     * @param text $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return text 
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/menu/'.$this->locale.'/';
    }

    /**
     * Set media
     *
     * @param text $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Get media
     *
     * @return text 
     */
    public function getMedia()
    {
        return $this->media;
    }


    /**
     * Set published
     *
     * @param boolean $published
     * @return Menu
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
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
     * Set link_taxonomy
     *
     * @param boolean $linkTaxonomy
     * @return Menu
     */
    public function setLinkTaxonomy($linkTaxonomy)
    {
        $this->link_taxonomy = $linkTaxonomy;
    
        return $this;
    }

    /**
     * Get link_taxonomy
     *
     * @return boolean 
     */
    public function getLinkTaxonomy()
    {
        return $this->link_taxonomy;
    }

    /**
     * Set urls
     *
     * @param string $urls
     * @return Menu
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
    
        return $this;
    }

    /**
     * Get urls
     *
     * @return string 
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Set category
     *
     * @param CAF\ContentBundle\Entity\Category $category
     * @return Menu
     */
    public function setCategory(\CAF\ContentBundle\Entity\CategoryTranslation $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return CAF\ContentBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set content
     *
     * @param CAF\ContentBundle\Entity\Content $content
     * @return Menu
     */
    public function setContent(\CAF\ContentBundle\Entity\ContentTranslation $content = null)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return CAF\ContentBundle\Content 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     * @return Menu
     */
    public function addCountrie(\CAF\AdminBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     */
    public function removeCountrie(\CAF\AdminBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Set countries
     *
     * @param CAF\AdminBundle\Entity\Country $countries
     */
    public function setCountries(\Doctrine\Common\Collections\ArrayCollection $countries)
    {
        $this->countries = $countries;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Menu
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    
        return $this;
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
     * Set urls_content
     *
     * @param string $urlsContent
     * @return Menu
     */
    public function setUrlsContent($urlsContent='',$canonicalContent='',$canonicalCategory='')
    {
        if($urlsContent != '')
            $this->urls_content = $urlsContent;
        else {
            if($canonicalContent) {
                if($canonicalContent) {
                    $this->urls_content = $canonicalContent;
                } else {
                    $url = $canonicalCategory;
                    $url .= '/'.$this->content->getAlias();
                    $this->urls_content = $url;
                }    
            } else if ($canonicalCategory) {
                if($canonicalCategory) {
                    $this->urls_content = $canonicalCategory;
                } else {
                    $alias = '';
                    $translations = $this->category->getTranslations();
                    foreach ($translations as $t) {
                        if($t->getLang()->getCode() == 'fr')
                            $alias = $t->getAlias();
                    }
                    $this->urls_content = $alias.'.html';
                }
            }
        }
        return $this;
    }

    /**
     * Get urls_content
     *
     * @return string 
     */
    public function getUrlsContent()
    {
        return $this->urls_content;
    }

    /**
     * Set menu_picto
     *
     * @param boolean $menuPicto
     * @return Menu
     */
    public function setMenuPicto($menuPicto)
    {
        $this->menu_picto = $menuPicto;
    
        return $this;
    }

    /**
     * Get menu_picto
     *
     * @return boolean 
     */
    public function getMenuPicto()
    {
        return $this->menu_picto;
    }
}