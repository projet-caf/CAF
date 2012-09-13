<?php
namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CAF\ContentBundle\Entity\Repository\CategoryRepository;

class CategoryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$meta_name = 'metasValues'.ucfirst($options['lang']);
		$name = 'translation'.ucfirst($options['lang']);
		$fichiers = $options['fichiers'];

		$builder
			->add($name, new CategoryTranslationType(), array(
                'label' 	=> ' ',
                ))
		    ->add($meta_name, 'metasvalues', array('label' => ' ', 'lang' => $options['lang'], 'canonical' => $options['canonical']))
		    ->add('parent', 'entity', array(
			    'class' => 'CAFContentBundle:Category',
			    'query_builder' => function(CategoryRepository $er) {
			        return $er->getCategoryParent();
			    },
				'empty_value' => 'Choisissez une catégorie parente',
				'required' => false,
			))
			->add('ordre','entity', array(
			    'class' => 'CAFContentBundle:Category',
			    'query_builder' => function(CategoryRepository $er) {
			        return $er->getCategoryParent();
			    },
				'empty_value' => "Après la catégorie",
				'required' => false,
			))
			->add('template', 'choice', array('choices' => $fichiers))
		;	
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'CAF\ContentBundle\Entity\Category', 'lang' => null, 'canonical' => '', 'fichiers' => ''));
    }

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\Category', 'lang' => 'fr', 'canonical' => '', 'fichiers' => '');
    }

	public function getName() {
		return 'category';
	}
}