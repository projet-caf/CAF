<?php

namespace CAF\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\ContentBundle\Entity\FieldsValue
 *
 * @ORM\Table(name="fieldsvalue")
 * @ORM\Entity(repositoryClass="CAF\ContentBundle\Entity\Repository\FieldsValueRepository")
 */
class FieldsValue
{

    /**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Fields")
     **/
    private $field;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ContentTranslation")
     **/
    private $content_translation;


    public function __toString()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return FieldsValue
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return unserialize($this->value);
    }

    /**
     * Set field
     *
     * @param CAF\ContentBundle\Entity\Fields $field
     * @return FieldsValue
     */
    public function setField(\CAF\ContentBundle\Entity\Fields $field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return CAF\ContentBundle\Entity\Fields 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set content_translation
     *
     * @param CAF\ContentBundle\Entity\ContentTranslation $contentTranslation
     * @return FieldsValue
     */
    public function setContentTranslation(\CAF\ContentBundle\Entity\ContentTranslation $contentTranslation)
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