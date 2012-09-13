<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class MetaValueType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('value','textarea');
	}
	
	public function getName() {
		return 'metas_value';
	}
}

?>