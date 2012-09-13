<?php

namespace CAF\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class MediaType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{

		$builder->add('directory', 'text', array(
			'label' => 'Répertoire',
			'required' => true,
			)
		);
	}
	
	public function getName() {
		return 'media';
	}
}	