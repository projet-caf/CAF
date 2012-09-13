<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class FieldTaxonomyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('name');
		$builder->add('balise','textarea');
	}

	public function getDefaultOptions(array $options)
    {
         $resolver->setDefaults(array('data_class' => 'CAF\ContentBundle\Entity\FieldTaxonomy'));
    }
	
	public function getName() {
		return 'field_taxonomy';
	}
}

?>