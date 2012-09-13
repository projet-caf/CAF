<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TranslationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name')
			->add('alias','text', array(
				'required' => false,
				)
			)
			->add('url')
			->add('parent', 'entity', array(
			    'class' => 'CAFMenuBundle:Menu',
				'empty_value' => 'Choose a parent menu',
				'required' => false,
				'property' => 'name',
			))
			->add('type', 'entity', array(
			    'class' => 'CAFMenuBundle:MenuTaxonomy',
				'empty_value' => 'Choose a menu',
				'property' => 'name',
			))
		;	
	}
	
	public function getName() {
		return 'menu';
	}
}

?>