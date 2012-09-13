<?php
namespace CAF\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
        $builder->add('image', 'file')
                ->add('alt', 'text')
                ->add('title', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->imageOptions
        ));
    }


    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'image';
    }
}