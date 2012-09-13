<?php
namespace CAF\BlocBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class LegendType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'image')
                ->add('first_line', 'text')
                ->add('second_line', 'text')
                /*->add('link', 'entity', array(
                    'class' => 'CAFContentBundle:Category',
                    'query_builder' => 
                        function(EntityRepository $er) {
                            return $er
                                    ->getCategoriesAndContent();
                        },
                    //'property' => 'title'
                    )
                )*/
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array());
    }


    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'legend';
    }
}