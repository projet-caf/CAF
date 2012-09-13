<?php

namespace CAF\AdminBundle\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class LanguageType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name')
			->add('code')
		;	
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\AdminBundle\Entity\Language');
    }
	
	public function getName() {
		return 'language';
	}
}

?>