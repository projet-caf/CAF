<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\BlocType;

use Doctrine\ORM\EntityRepository;

class BlocBannerSlideType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('bloc', new BlocType)
			//->add('images', 'legend')
			->add('images', 'collection', array( 'type' => 'legend', 'prototype' => true, 'allow_add' => true))
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\BlocBannerSlide');
    }

	public function getName() {
		return 'bloc_banner_slide';
	}
}