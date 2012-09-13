<?php
namespace CAF\MenuBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LinkTaxonomyType extends AbstractType
{

	private $linkChoices;

    public function __construct(array $linkChoices)
    {
        $this->linkChoices = $linkChoices;
    }


	public function getDefaultOptions(array $options)
    {
        return array(
            'choices' => $this->linkChoices,
            'expanded' => true
        );
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'link';
    }
}