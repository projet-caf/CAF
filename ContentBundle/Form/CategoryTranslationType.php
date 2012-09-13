<?php
namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryTranslationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title')
			->add('alias')
			->add('published', 'publish')
		    ->add('description','ckeditor', array('required' => false))
		    ->add('url', 'hidden')
		;	
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\CategoryTranslation');
    }

    

	public function getName() {
		return 'category_translation';
	}
}