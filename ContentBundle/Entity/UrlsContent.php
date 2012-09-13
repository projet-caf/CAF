<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\DriverManager;

/**
 * CAF\ContentBundle\Entity\Content
 *
 * @ORM\Table(name="urls_content")
 * @ORM\Entity()
 */
class UrlsContent
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
     * @ORM\ManyToOne(targetEntity="ContentTranslation")
     * @ORM\JoinColumn(name="content_id")
     */
    private $content_translation;

    /**
     * @ORM\Column(name="url", type="string", length=255)
     **/
    private $url;


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
     * Set url
     *
     * @param string $url
     * @return UrlsContent
     */
    public function setUrl($url='')
    {
       
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set content_translation
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentTranslation
     * @return UrlsContent
     */
    public function setContentTranslation(\CAF\ContentBundle\Entity\ContentTranslation $contentTranslation = null)
    {
        $this->content_translation = $contentTranslation;
    
        return $this;
    }

    /**
     * Get content_translation
     *
     * @return CAF\ContentBundle\Entity\ContentTranslation 
     */
    public function getContentTranslation()
    {
        return $this->content_translation;
    }
}