<?php

namespace CAF\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\FormBundle\Entity\FormFieldsValue
 *
 * @ORM\Table(name="formfieldsvalue")
 * @ORM\Entity(repositoryClass="CAF\FormBundle\Entity\Repository\FormFieldsValueRepository")
 */
class FormFieldsValue
{

    /**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="FormFields")
     **/
    private $formfield;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Formulaire")
     **/
    private $formulaire;

    public function __toString()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return FormFieldsValue
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
        return $this->value;
    }


    /**
     * Set formfield
     *
     * @param CAF\FormBundle\Entity\FormFields $formfield
     * @return FormFieldsValue
     */
    public function setFormfield(\CAF\FormBundle\Entity\FormFields $formfield)
    {
        $this->formfield = $formfield;
    
        return $this;
    }

    /**
     * Get formfield
     *
     * @return CAF\FormBundle\Entity\FormFields 
     */
    public function getFormfield()
    {
        return $this->formfield;
    }

    /**
     * Set formulaire
     *
     * @param CAF\FormBundle\Entity\formulaire $formulaire
     * @return FormFieldsValue
     */
    public function setFormulaire(\CAF\FormBundle\Entity\formulaire $formulaire)
    {
        $this->formulaire = $formulaire;
    
        return $this;
    }

    /**
     * Get formulaire
     *
     * @return CAF\FormBundle\Entity\formulaire 
     */
    public function getFormulaire()
    {
        return $this->formulaire;
    }
}