<?php

namespace CAF\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\AdminBundle\Entity\Banner
 *
 * @ORM\Table(name="banner")
 * @ORM\Entity()
 */
class Banner
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
     * @var string $campaign_name
     * 
     * @ORM\Column(name="campaign_name", type="string")
     */
    private $campaign_name;

    /**
     * @var string $alias_campaign_name
     * 
     * @ORM\Column(name="alias_campaign_name", type="string")
     */
    private $alias_campaign_name;

    /**
     * @var boolean $published
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;
    
    /**
     * @var string $banner_name
     *
     * @ORM\Column(name="banner_name", type="string")
     */
    private $banner_name;

    /**
     * @var string $alias_banner_name
     *
     * @ORM\Column(name="alias_banner_name", type="string")
     */
    private $alias_banner_name;

    /**
     * @var string $file
     *
     * @ORM\Column(name="file", type="string")
     */
    private $file;

    /**
     * @var string $link
     *
     * @ORM\Column(name="link", type="string")
     */
    private $link;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;


    public function __construct()
    {
        $this->page = new \Doctrine\Common\Collections\ArrayCollection();
        $this->path ='/media/bannieres';
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

    /**
     * Set campaign_name
     *
     * @param string $campaignName
     */
    public function setCampaignName($campaignName)
    {
        $this->campaign_name = $campaignName;
    }

    /**
     * Get campaign_name
     *
     * @return string 
     */
    public function getCampaignName()
    {
        return $this->campaign_name;
    }

    /**
     * Set banner_name
     *
     * @param string $bannerName
     */
    public function setBannerName($bannerName)
    {
        $this->banner_name = $bannerName;
    }

    /**
     * Get banner_name
     *
     * @return string 
     */
    public function getBannerName()
    {
        return $this->banner_name;
    }

    /**
     * Get page
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     * Add page
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $page
     */
    public function addContentTranslation(\CAF\ContentBundle\Entity\ContentTranslation $page)
    {
        $this->page[] = $page;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set alias_campaign_name
     *
     * @param string $aliasCampaignName
     */
    public function setAliasCampaignName($aliasCampaignName)
    {
       if($aliasCampaignName != '') {
            $this->alias_campaign_name = $aliasCampaignName;
        } else {
            $this->alias_campaign_name = $this->stringURLSafe($this->getCampaignName());
        }
    }

    /**
     * Get alias_campaign_name
     *
     * @return string 
     */
    public function getAliasCampaignName()
    {
        return $this->alias_campaign_name;
    }

    /**
     * Set alias_banner_name
     *
     * @param string $aliasBannerName
     */
    public function setAliasBannerName($aliasBannerName)
    {
        if($aliasBannerName != '') {
            $this->alias_banner_name = $aliasBannerName;
        } else {
            $this->alias_banner_name = $this->stringURLSafe($this->getBannerName());
        }
    }

    /**
     * Get alias_banner_name
     *
     * @return string 
     */
    public function getAliasBannerName()
    {
        return $this->alias_banner_name;
    }

    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
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
        return 'uploads';
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

      private function transliterate($string)
    {
        $string = htmlentities(utf8_decode($string));
        $string = preg_replace(
            array('/&szlig;/','/&(..)lig;/', '/&([aouAOU])uml;/','/&(.)[^;]*;/'),
            array('ss',"$1","$1".'e',"$1"),
            $string);

        return $string;
    }

    private function stringURLSafe($string)
    {
        //remove any '-' from the string they will be used as concatonater
        $str = str_replace('-', ' ', $string);

        $str = $this->transliterate($str);

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);

        // lowercase and trim
        $str = trim(strtolower($str));
        return $str;
    }

    public function getshowfile(){
        $src_img=$this->getUploadDir().'/'.$this->path.'/'.$this->file;
        return $src_img;
    }

     /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}