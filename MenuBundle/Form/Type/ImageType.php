<?php
namespace CAF\MenuBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use CAF\ContentBundle\Form\DataTransformer\ImageToArrayTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{

    private $imageOptions;

    public function __construct(array $imageOptions)
    {
        $this->imageOptions = $imageOptions;
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'file', array('required' => false))
                ->add('alt', 'text', array('required' => false))
                ->add('title', 'text', array('required' => false));
        /*$transformer = new ImageToArrayTransformer();
        $builder->prependNormTransformer($transformer);*/
    }


    public function getDefaultOptions(array $options)
    {
        return array('image' => '', 'alt' => '', 'title' => '');
    }

    public function getParent()
    {
        return 'field';
    }

    public function getName()
    {
        return 'image';
    }
}