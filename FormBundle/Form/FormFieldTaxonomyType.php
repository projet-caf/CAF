<?php

namespace CAF\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class FormFieldTaxonomyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('name');
		$builder->add('balise','textarea');
	}

	public function getDefaultOptions(array $options)
    {
       return array('data_class' => 'CAF\FormBundle\Entity\FormFieldTaxonomy');
    }
	
	public function getName() {
		return 'formfield_taxonomy';
	}
}

?>