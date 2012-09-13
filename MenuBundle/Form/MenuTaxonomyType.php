<?php

namespace CAF\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuTaxonomyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name')
			->add('alias','text', array(
				'required' => false
				)
			)
		;	
	}
	
	public function getName() {
		return 'menu_taxonomy';
	}
}