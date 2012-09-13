<?php
namespace CAF\MenuBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PublishType extends AbstractType
{

	private $publishChoices;

    public function __construct(array $publishChoices)
    {
        $this->publishChoices = $publishChoices;
    }


	public function getDefaultOptions(array $options)
    {
        return array(
            'choices' => $this->publishChoices,
            'expanded' => true
        );
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'publish';
    }
}