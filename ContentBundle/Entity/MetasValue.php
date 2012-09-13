<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\Metas
 *
 * @ORM\Table(name="metasvalue")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\MetasValueRepository")
 */
class MetasValue
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Metas")
     **/
    private $meta;

    /**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ContentTranslation")
     **/
    private $content_translation;

    /*
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CategoryTranslation")
     **/
    private $category_translation;


    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return text 
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Set meta
     *
     * @param CAF\ContentBundle\Entity\Metas $meta
     */
    public function setMeta(\CAF\ContentBundle\Entity\Metas $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Get meta
     *
     * @return CAF\ContentBundle\Entity\Metas 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    public function __toString()
    {
        return $this->value;
    }

    /**
     * Set content_translation
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentTranslation
     */
    public function setContentTranslation(\CAF\ContentBundle\Entity\ContentTranslation $contentTranslation)
    {
        $this->content_translation = $contentTranslation;
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

    /**
     * Set category_translation
     *
     * @param CAF\ContentBundle\Entity\CategoryTranslation $categoryTranslation
     */
    public function setCategoryTranslation(\CAF\ContentBundle\Entity\CategoryTranslation $categoryTranslation)
    {
        $this->category_translation = $categoryTranslation;
    }

    /**
     * Get category_translation
     *
     * @return CAF\ContentBundle\Entity\CategoryTranslation 
     */
    public function getCategoryTranslation()
    {
        return $this->category_translation;
    }
}